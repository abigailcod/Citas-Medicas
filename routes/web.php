<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BackupController; // ✅ NUEVO
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // ========================================
    // Rutas de Perfil
    // ========================================
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ========================================
    // Rutas de Doctores (Solo Admin)
    // ========================================
    Route::resource('doctors', DoctorController::class);
    
    // ========================================
    // Rutas de Pacientes (Solo Admin)
    // ========================================
    Route::resource('patients', PatientController::class);
    
    // ========================================
    // Rutas de Citas (Appointments)
    // ========================================
    Route::resource('appointments', AppointmentController::class);
    
    // ========================================
    // ✅ Rutas de Backups (Solo Admin)
    // ========================================
    Route::middleware('role:admin')->group(function () {
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups/create', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
    });
});

require __DIR__.'/auth.php';