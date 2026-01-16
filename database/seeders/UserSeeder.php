<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User (IT)
        $admin = User::create([
            'name' => 'Admin IT',
            'email' => 'admin@terakorp.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // HR User (Operasional)
        $hr = User::create([
            'name' => 'HR Manager',
            'email' => 'hr@terakorp.com',
            'password' => bcrypt('password'),
            'role' => 'hr',
            'email_verified_at' => now(),
        ]);

        // Employee Users (Karyawan)
        $emp1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@terakorp.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        $emp2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@terakorp.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        // Create employees
        Employee::create([
            'user_id' => $emp1->id,
            'name' => 'Budi Santoso',
            'telegram_id' => '123456789',
            'department' => 'Finance',
            'position' => 'Senior Analyst',
        ]);

        Employee::create([
            'name' => 'Ahmad Wijaya',
            'telegram_id' => '555666777',
            'department' => 'IT',
            'position' => 'Developer',
        ]);

        Employee::create([
            'name' => 'Muhamad Rafly',
            'telegram_id' => '1489907217',
            'department' => 'IT Security System',
            'position' => 'RND',
        ]);
    }
}
