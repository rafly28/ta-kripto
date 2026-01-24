<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Cryptography\AES256Engine;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PayrollController extends Controller
{
    protected $aes;
    
    public function __construct(AES256Engine $aes)
    {
        $this->aes = $aes;
    }

    public function index()
    {
        // Get employee list untuk dropdown
        $employees = \App\Models\Employee::all();
        return view('payroll.upload', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'file' => 'required|file|max:10240',
        ]);

        try {
            $employee = \App\Models\Employee::find($request->employee_id);
            $file = $request->file('file');
            $plainContent = file_get_contents($file->getRealPath());
            $password = Str::random(12);

            $aes = new AES256Engine();
            $startTime = microtime(true);
            $encryptedContent = $aes->encrypt($plainContent, $password);
            $endTime = microtime(true);
            $waktuEnkripsi = ($endTime - $startTime) * 1000;

            $fileName = time() . '_' . str_replace(' ', '_', $employee->name) . '.' . $file->getClientOriginalExtension() . '.enc';
            Storage::put('payrolls/' . $fileName, $encryptedContent);
            $ukuranEnkripsi = Storage::size('payrolls/' . $fileName);

            // simpan dan ambil model yang dibuat
            $created = Payroll::create([
                'user_id' => $employee->user_id,
                'employee_name' => $employee->name,
                'telegram_id' => $employee->telegram_id,
                'encrypted_file_path' => 'payrolls/' . $fileName,
                'dynamic_pass' => hash('sha256', $password),
                'waktu_enkripsi' => $waktuEnkripsi,
                'ukuran_asli' => strlen($plainContent),
                'ukuran_enkripsi' => $ukuranEnkripsi,
                'uploader_id' => auth()->id(),
            ]);

            $this->sendToTelegram($employee->telegram_id, $employee->name, $password, $fileName);

            // kembalikan last upload id supaya HR bisa langsung download .enc
            return redirect()->route('payroll.upload')
                ->with('success', 'File uploaded successfully!')
                ->with('last_upload_id', $created->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    private function sendToTelegram($chatId, $name, $password, $fileName)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        
        if (!$token) {
            \Log::error('TELEGRAM_BOT_TOKEN not set in .env');
            return false;
        }
        
        $message = "Dear <b>{$name}</b>,\n\n";
        $message .= "Slip gaji Anda tersedia: <code>{$fileName}</code>\n";
        $message .= "Password dekripsi:\n\n";
        $message .= "<tg-spoiler><b>{$password}</b></tg-spoiler>\n\n";
        $message .= "🔒 Jangan share password ini!";

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId, // ✅ FIXED: Add missing chat_id
                'text'       => $message,
                'parse_mode' => 'HTML',
            ]);
            
            \Log::info('Telegram API call', [
                'chat_id' => $chatId,
                'status' => $response->status(),
                'success' => $response->successful()
            ]);
            
            if (!$response->successful()) {
                \Log::error("Telegram error", ['response' => $response->json()]);
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error("Telegram exception", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function download($id)
    {
        $payroll = Payroll::findOrFail($id);
        $user = auth()->user();

        // Allow if admin, hr, file owner, or uploader
        $canDownload = false;
        if ($user->isAdmin() || $user->isHR()) {
            $canDownload = true;
        } elseif ($payroll->user_id && $payroll->user_id === $user->id) {
            $canDownload = true;
        } elseif ($payroll->uploader_id && $payroll->uploader_id === $user->id) {
            $canDownload = true;
        }

        if (! $canDownload) {
            abort(403, 'Unauthorized to download this file.');
        }

        $path = $payroll->encrypted_file_path;
        if (! Storage::exists($path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($path, basename($path));
    }

    public function data()
    {
        $payrolls = Payroll::latest()->get();
        return response()->json($payrolls);
    }

    public function decryptForm()
    {
        return view('payroll.decrypt');
    }

    public function decryptProcess(Request $request)
    {
        $request->validate([
            'file_enc' => 'required|file',
            'password' => 'required|string'
        ]);

        try {
            $content = file_get_contents($request->file('file_enc')->getRealPath());
            $decrypted = $this->aes->decrypt($content, $request->password);

            if ($decrypted === false) {
                return back()->with('error', 'Password salah atau data rusak!');
            }

            \Log::info('Payroll decrypted successfully', [
                'timestamp' => now()
            ]);

            return response()->streamDownload(function () use ($decrypted) {
                echo $decrypted;
            }, 'slip_gaji_asli.pdf', [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            \Log::error('Decryption error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function myFiles()
    {
        // User hanya bisa lihat file yang di-assign ke dia
        $payrolls = Payroll::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('payroll.my-files', compact('payrolls'));
    }
}