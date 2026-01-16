<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'hr', 'employee'])->default('employee')->after('email');
            }
        });
    }

    public function down(): void
    {
        // Jangan drop apapun di down
    }
};