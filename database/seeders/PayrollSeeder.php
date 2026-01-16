<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payroll;
use App\Cryptography\AES256Engine;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $aes = new AES256Engine();
        $employees = [
            'Budi Santoso', 'Siti Nurhaliza', 'Ahmad Wijaya', 'Ratna Dewi', 
            'Hendrik Kusuma', 'Maya Insani', 'Rian Hermawan', 'Dina Marlina',
            'Bambang Suryanto', 'Lestari Wijayanti', 'Rinto Harahap', 'Fiona Natasha',
            'Gunawan Pratama', 'Hesti Ramadhani', 'Irwan Setiawan'
        ];

        $fileSizes = [
            'small' => 250000,   // ~250KB
            'medium' => 1048576, // ~1MB
            'large' => 2500000   // ~2.5MB
        ];

        $fileTypes = 'xlsx';

        foreach ($employees as $index => $name) {
            $sizeType = array_keys($fileSizes)[($index % 3)];
            $fileType = $fileTypes[$index % 3];
            $fileSize = $fileSizes[$sizeType];

            // Generate dummy file content
            $plainContent = str_repeat('Slip Gaji ' . $name . ' ' . now()->format('Y-m-d') . ' ', $fileSize / 100);
            $plainContent = substr($plainContent, 0, $fileSize);

            $password = Str::random(12);

            $startTime = microtime(true);
            $encryptedContent = $aes->encrypt($plainContent, $password);
            $endTime = microtime(true);
            $waktuEnkripsi = ($endTime - $startTime) * 1000;

            $fileName = time() + $index . '_' . strtolower(str_replace(' ', '_', $name)) . '.' . $fileType . '.enc';
            Storage::put('payrolls/' . $fileName, $encryptedContent);
            $ukuranEnkripsi = Storage::size('payrolls/' . $fileName);

            Payroll::create([
                'employee_name'       => $name,
                'telegram_id'         => '148990' . str_pad($index, 4, '0', STR_PAD_LEFT),
                'encrypted_file_path' => 'payrolls/' . $fileName,
                'dynamic_pass'        => hash('sha256', $password),
                'waktu_enkripsi'      => $waktuEnkripsi,
                'ukuran_asli'         => $fileSize,
                'ukuran_enkripsi'     => $ukuranEnkripsi,
            ]);

            echo "✓ Generated: $name ($sizeType - $fileType)\n";
        }
    }
}