<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayrollController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/payroll/upload', [PayrollController::class, 'index'])->name('payroll.upload'); // Tampilkan form & tabel
    Route::post('/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/download/{id}', [PayrollController::class, 'download'])->name('payroll.download');
    
    Route::get('/payroll/decrypt', function() { return view('payroll.decrypt'); })->name('payroll.decrypt');
    Route::post('/payroll/decrypt-process', [PayrollController::class, 'decryptProcess'])->name('payroll.decrypt.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/upload', function () {
        return view('payroll.upload');
    })->name('payroll.upload');
});
require __DIR__.'/auth.php';
