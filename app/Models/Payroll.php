<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_name',
        'telegram_id',
        'encrypted_file_path',
        'dynamic_pass',
        'waktu_enkripsi',
        'ukuran_asli',
        'ukuran_enkripsi',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}