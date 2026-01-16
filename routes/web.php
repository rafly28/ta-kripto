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
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Payroll routes
    Route::get('/payroll/upload', [PayrollController::class, 'index'])->name('payroll.upload');
    Route::post('/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/download/{id}', [PayrollController::class, 'download'])->name('payroll.download');
    Route::get('/payroll/data', [PayrollController::class, 'data'])->name('payroll.data');
    Route::get('/payroll/decrypt', [PayrollController::class, 'decryptForm'])->name('payroll.decrypt');
    Route::post('/payroll/decrypt-process', [PayrollController::class, 'decryptProcess'])->name('payroll.decrypt.process');
});

require __DIR__.'/auth.php';
