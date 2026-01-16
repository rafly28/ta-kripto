<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ADMIN ONLY
    Route::middleware('role:admin')->group(function () {
        // Employee Management
        Route::resource('employee', EmployeeController::class);
        Route::get('/employee/get-telegram/{name}', [EmployeeController::class, 'getTelegramId'])->name('employee.telegram');
        
        // Payroll Upload
        Route::get('/payroll/upload', [PayrollController::class, 'index'])->name('payroll.upload');
        Route::post('/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
        
        // Analytics
        Route::get('/admin/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
        Route::get('/dashboard/export', [DashboardController::class, 'exportCsv'])->name('dashboard.export');
    });

    // HR ONLY
    Route::middleware('role:hr')->group(function () {
        // Employee Management (HR bisa lihat, edit, tambah)
        Route::resource('employee', EmployeeController::class);
        Route::get('/employee/get-telegram/{name}', [EmployeeController::class, 'getTelegramId'])->name('employee.telegram');
        
        // Payroll Upload
        Route::get('/payroll/upload', [PayrollController::class, 'index'])->name('payroll.upload');
        Route::post('/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
        
        // HR Analytics (simplified)
        Route::get('/hr/analytics', [DashboardController::class, 'hrAnalytics'])->name('hr.analytics');
    });

    // EMPLOYEE (User) - Decrypt & Personal Files
    Route::get('/payroll/decrypt', [PayrollController::class, 'decryptForm'])->name('payroll.decrypt');
    Route::post('/payroll/decrypt-process', [PayrollController::class, 'decryptProcess'])->name('payroll.decrypt.process');
    Route::get('/payroll/my-files', [PayrollController::class, 'myFiles'])->name('payroll.my-files');
    Route::get('/payroll/download/{id}', [PayrollController::class, 'download'])->name('payroll.download');
});

require __DIR__.'/auth.php';
