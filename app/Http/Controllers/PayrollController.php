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
        \Log::info('Payroll upload: masuk ke method store', $request->all());

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'file' => 'required|file|max:10240',
        ]);

        try {
            \Log::info('Payroll upload: validasi sukses');

            $employee = \App\Models\Employee::find($request->employee_id);
            if (!$employee) {
                \Log::error('Payroll upload: employee tidak ditemukan', ['employee_id' => $request->employee_id]);
                throw new \Exception('Employee not found');
            }

            $file = $request->file('file');
            \Log::info('Payroll upload: file ditemukan', ['file' => $file->getClientOriginalName()]);

            $plainContent = file_get_contents($file->getRealPath());
            $password = Str::random(12);

            $aes = new AES256Engine();
            $startTime = microtime(true);
            $encryptedContent = $aes->encrypt($plainContent, $password);
            $endTime = microtime(true);
            $waktuEnkripsi = ($endTime - $startTime) * 1000;

            $periode = date('Y');
            $bulan = date('m');
            $namaKaryawan = str_replace(' ', '_', $employee->name);
            $ext = $file->getClientOriginalExtension();
            $fileName = "{$periode}-{$bulan}-{$namaKaryawan}.{$ext}.enc";

            \Log::info('Payroll upload: siap simpan file', ['fileName' => $fileName]);

            Storage::put('payrolls/' . $fileName, $encryptedContent);
            $ukuranEnkripsi = Storage::size('payrolls/' . $fileName);

            $created = Payroll::create([
                'employee_id' => $request->employee_id, // pastikan ini ada
                'encrypted_file_path' => 'payrolls/' . $fileName,
                'periode' => $periode,
                'bulan' => $bulan,
                'dynamic_pass' => hash('sha256', $password),
                'waktu_enkripsi' => $waktuEnkripsi,
                'ukuran_asli' => strlen($plainContent),
                'ukuran_enkripsi' => $ukuranEnkripsi,
            ]);

            \Log::info('Payroll upload: payroll berhasil dibuat', ['payroll_id' => $created->id]);

            $this->sendToTelegram($employee->telegram_id, $employee->name, $password, $fileName);

            return redirect()->route('payroll.upload')
                ->with('success', 'File uploaded successfully!')
                ->with('last_upload_id', $created->id);
        } catch (\Exception $e) {
            \Log::error('Payroll upload: gagal', ['error' => $e->getMessage()]);
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

    public function myFiles()
    {
        $user = auth()->user();

        $payrolls = \App\Models\Payroll::whereHas('employee', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orderBy('created_at', 'desc')->get();

        return view('payroll.my-files', compact('payrolls'));
    }

    public function decryptForm()
    {
        return view('payroll.decrypt');
    }

    public function decryptProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'password' => 'required|string'
        ]);

        $user = auth()->user();
        $uploadedFile = $request->file('file');
        $uploadedName = $uploadedFile->getClientOriginalName();

        $payroll = \App\Models\Payroll::where('encrypted_file_path', 'like', "%{$uploadedName}")->first();

        if (!$payroll) {
            return back()->with('error', 'File tidak dikenali di sistem.');
        }

        if ($user->role !== 'hr' && $payroll->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $content = file_get_contents($uploadedFile->getRealPath());
        $decrypted = $this->aes->decrypt($content, $request->password);

        if ($decrypted === false || empty($decrypted)) {
            return back()->with('error', 'Password salah atau file rusak!');
        }

        // Nama file hasil dekripsi = nama file asli payroll (tanpa .enc)
        $originalName = preg_replace('/\.enc$/', '', basename($payroll->encrypted_file_path));

        // Pastikan header sesuai file asli (misal PDF, XLSX, dsb)
        $mime = 'application/octet-stream';
        if (Str::endsWith($originalName, '.pdf')) {
            $mime = 'application/pdf';
        } elseif (Str::endsWith($originalName, '.xlsx')) {
            $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }

        return response()->streamDownload(function () use ($decrypted) {
            echo $decrypted;
        }, $originalName, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="'.$originalName.'"',
        ]);
    }
}