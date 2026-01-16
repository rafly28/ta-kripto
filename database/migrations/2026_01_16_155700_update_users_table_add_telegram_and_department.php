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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_id')->nullable()->unique()->after('email');
            $table->string('department')->nullable()->after('telegram_id');
            // Update role enum
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'hr', 'employee'])->default('employee')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_id', 'department', 'role']);
        });
    }
};
