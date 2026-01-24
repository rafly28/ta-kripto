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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->float('waktu_enkripsi')->nullable();
            $table->bigInteger('ukuran_asli')->nullable();
            $table->bigInteger('ukuran_enkripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['waktu_enkripsi', 'ukuran_asli', 'ukuran_enkripsi']);
        });
    }
};
