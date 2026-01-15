<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('telegram_id');
            $table->string('encrypted_file_path');
            $table->string('dynamic_pass');
                
            $table->double('waktu_enkripsi');
            $table->bigInteger('ukuran_asli');
            $table->bigInteger('ukuran_enkripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
