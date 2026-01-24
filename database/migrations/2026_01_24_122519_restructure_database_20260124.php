<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RestructureDatabase20260124 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop payrolls & employees jika sudah ada
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('employees');

        // Pastikan kolom role di users
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['hr', 'user'])->default('user')->after('password');
            });
        }

        // Buat tabel employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telegram_id')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Buat tabel payrolls
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('encrypted_file_path');
            $table->string('periode');
            $table->string('bulan');
            $table->string('dynamic_pass');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('employees');

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
}
