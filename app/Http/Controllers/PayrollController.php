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

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'telegram_id'   => 'required|string',
            'file_slip'     => 'required|file|mimes:pdf,xlsx,docx|max:5000',
        ]);

        $file = $request->file('file_slip');
        $plainContent = file_get_contents($file->getRealPath());
        $ukuranAsli = $file->getSize();
        $dynamicPassword = Str::random(12); 

        $startTime = microtime(true);
        $encryptedContent = $this->aes->encrypt($plainContent, $dynamicPassword);
        $endTime = microtime(true);
        
        $waktuEnkripsi = $endTime - $startTime;

        $fileName = time() . '_' . $file->getClientOriginalName() . '.enc';
        Storage::put('payrolls/' . $fileName, $encryptedContent);
        $ukuranEnkripsi = Storage::size('payrolls/' . $fileName);

        Payroll::create([
            'employee_name'       => $request->employee_name,
            'telegram_id'         => $request->telegram_id,
            'encrypted_file_path' => 'payrolls/' . $fileName,
            'dynamic_pass'        => $dynamicPassword,
            'waktu_enkripsi'      => $waktuEnkripsi,
            'ukuran_asli'         => $ukuranAsli,
            'ukuran_enkripsi'     => $ukuranEnkripsi,
        ]);
        $this->sendToTelegram($request->telegram_id, $request->employee_name, $dynamicPassword, $fileName);
        return back()->with('success', 'File berhasil dienkripsi dan password telah dikirim ke Telegram Karyawan!');
    }

    private function sendToTelegram($chatId, $name, $password, $fileName)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        
        // Gunakan tag HTML agar lebih stabil
        $message = "Dear <b>{$name}</b>,\n\n";
        $message .= "Slip gaji Anda bulan ini sudah tersedia dengan nama <code>{$fileName}</code>.\n";
        $message .= "Gunakan password berikut untuk dekripsi:\n\n";
        $message .= "<tg-spoiler><b>{$password}</b></tg-spoiler>\n\n";
        $message .= "Jangan memberikan password ini kepada siapapun.";

        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
            
            if (!$response->successful()) {
                \Log::error("Telegram API Error: " . $response->body());
            }
            
            return $response->successful();
        } catch (\Exception $e) {
            \Log::error("Telegram Connection Error: " . $e->getMessage());
            return false;
        }
    }

    public function download($id) {
        $payroll = Payroll::findOrFail($id);
        return Storage::download($payroll->encrypted_file_path);
    }

    public function decryptProcess(Request $request) {
        $request->validate([
            'file_enc' => 'required|file',
            'password' => 'required'
        ]);

        $content = file_get_contents($request->file('file_enc')->getRealPath());
        
        $decrypted = $this->aes->decrypt($content, $request->password);

        if (!$decrypted) {
            return back()->with('error', 'Password salah atau data rusak!');
        }

        return response()->streamDownload(function () use ($decrypted) {
            echo $decrypted;
        }, 'slip_gaji_asli.pdf');
    }

}