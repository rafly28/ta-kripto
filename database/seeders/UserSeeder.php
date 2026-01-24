<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update core users
        $admin = User::updateOrCreate(
            ['email' => 'admin@terakorp.com'],
            [
                'name' => 'Admin IT',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'telegram_id' => null,
            ]
        );

        $hr = User::updateOrCreate(
            ['email' => 'hr@terakorp.com'],
            [
                'name' => 'HR Manager',
                'password' => bcrypt('password'),
                'role' => 'hr',
                'email_verified_at' => now(),
                'telegram_id' => null,
            ]
        );

        // Employee users (create if missing)
        $emp1 = User::updateOrCreate(
            ['email' => 'budi@terakorp.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'role' => 'employee',
                'email_verified_at' => now(),
            ]
        );

        $emp2 = User::updateOrCreate(
            ['email' => 'siti@terakorp.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => bcrypt('password'),
                'role' => 'employee',
                'email_verified_at' => now(),
            ]
        );

        $emp3 = User::updateOrCreate(
            ['email' => 'ahmad@terakorp.com'],
            [
                'name' => 'Ahmad Wijaya',
                'password' => bcrypt('password'),
                'role' => 'employee',
                'email_verified_at' => now(),
            ]
        );

        $emp4 = User::updateOrCreate(
            ['email' => 'rafly@terakorp.com'],
            [
                'name' => 'Muhamad Rafly',
                'password' => bcrypt('password'),
                'role' => 'employee',
                'email_verified_at' => now(),
            ]
        );

        // Employees list — updateOrCreate by telegram_id (unique)
        Employee::updateOrCreate(
            ['telegram_id' => '123456789'],
            [
                'user_id' => $emp1->id,
                'name' => 'Budi Santoso',
                'email' => $emp1->email,
                'department' => 'Finance',
                'position' => 'Senior Analyst',
            ]
        );

        Employee::updateOrCreate(
            ['telegram_id' => '555666777'],
            [
                'user_id' => $emp3->id,
                'name' => 'Ahmad Wijaya',
                'email' => $emp3->email,
                'department' => 'IT',
                'position' => 'Developer',
            ]
        );

        Employee::updateOrCreate(
            ['telegram_id' => '1489907217'],
            [
                'user_id' => $emp4->id,
                'name' => 'Muhamad Rafly',
                'email' => $emp4->email,
                'department' => 'IT Security System',
                'position' => 'RND',
            ]
        );

        // Optional extra employee without linked user (if needed)
        // Employee::updateOrCreate(
        //     ['telegram_id' => '000111222'],
        //     [
        //         'user_id' => null,
        //         'name' => 'Guest Employee',
        //         'email' => null,
        //         'department' => 'Ops',
        //         'position' => 'Staff',
        //     ]
        // );
    }
}
