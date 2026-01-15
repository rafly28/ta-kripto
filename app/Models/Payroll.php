<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_name',
        'telegram_id',
        'encrypted_file_path',
        'dynamic_pass',
        'waktu_enkripsi',
        'ukuran_asli',
        'ukuran_enkripsi',
    ];

    protected $casts = [
        'waktu_enkripsi' => 'double',
        'ukuran_asli' => 'integer',
        'ukuran_enkripsi' => 'integer',
    ];
}