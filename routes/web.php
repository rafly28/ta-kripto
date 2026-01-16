<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\DashboardController;
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
    // Dashboard - Simple version untuk semua user
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ADMIN ONLY - Payroll Upload & Analytics
    Route::middleware('role:admin')->group(function () {
        Route::get('/payroll/upload', [PayrollController::class, 'index'])->name('payroll.upload');
        Route::post('/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
        
        // Data Analytics (Dashboard lengkap)
        Route::get('/admin/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
        Route::get('/dashboard/export', [DashboardController::class, 'exportCsv'])->name('dashboard.export');
    });

    // USER & ADMIN - Decrypt & Personal Files
    Route::get('/payroll/decrypt', [PayrollController::class, 'decryptForm'])->name('payroll.decrypt');
    Route::post('/payroll/decrypt-process', [PayrollController::class, 'decryptProcess'])->name('payroll.decrypt.process');
    Route::get('/payroll/my-files', [PayrollController::class, 'myFiles'])->name('payroll.my-files');
    Route::get('/payroll/download/{id}', [PayrollController::class, 'download'])->name('payroll.download');
});

require __DIR__.'/auth.php';
