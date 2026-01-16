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
        $payrolls = Payroll::latest()->get();
        return view('payroll.upload', compact('payrolls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'telegram_id'   => 'required|string',
            'file_slip'     => 'required|file|mimes:pdf,xlsx,docx|max:5000',
        ]);

        try {
            $file = $request->file('file_slip');
            $plainContent = file_get_contents($file->getRealPath());
            $ukuranAsli = $file->getSize();
            $dynamicPassword = Str::random(12);

            \Log::info('File uploaded', [
                'name' => $file->getClientOriginalName(),
                'size' => $ukuranAsli,
                'password' => $dynamicPassword // Remove in production!
            ]);

            $startTime = microtime(true);
            $encryptedContent = $this->aes->encrypt($plainContent, $dynamicPassword);
            $endTime = microtime(true);
            
            $waktuEnkripsi = ($endTime - $startTime) * 1000;

            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.]/', '', $file->getClientOriginalName()) . '.enc';
            Storage::put('payrolls/' . $fileName, $encryptedContent);
            $ukuranEnkripsi = Storage::size('payrolls/' . $fileName);

            \Log::info('Encryption completed', [
                'filename' => $fileName,
                'duration_ms' => $waktuEnkripsi,
                'original_size' => $ukuranAsli,
                'encrypted_size' => $ukuranEnkripsi
            ]);

            Payroll::create([
                'employee_name'       => $request->employee_name,
                'telegram_id'         => $request->telegram_id,
                'encrypted_file_path' => 'payrolls/' . $fileName,
                'dynamic_pass'        => hash('sha256', $dynamicPassword),
                'waktu_enkripsi'      => $waktuEnkripsi,
                'ukuran_asli'         => $ukuranAsli,
                'ukuran_enkripsi'     => $ukuranEnkripsi,
            ]);
            
            $telegramSent = $this->sendToTelegram(
                $request->telegram_id, 
                $request->employee_name, 
                $dynamicPassword, 
                $fileName
            );
            
            if ($telegramSent) {
                return back()->with('success', "✅ File berhasil dienkripsi & OTP dikirim ke Telegram!");
            } else {
                return back()->with('warning', "✅ File enkripsi OK, tapi Telegram gagal. Check logs!");
            }
            
        } catch (\Exception $e) {
            \Log::error('Encryption error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
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
        return Storage::download($payroll->encrypted_file_path);
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
}