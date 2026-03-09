<?php

use App\Http\Controllers\ClientePdfController;
use App\Http\Controllers\ProfileController;
use App\Livewire\ClienteWizard;
use App\Livewire\Dashboard\TablaClientes;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', TablaClientes::class)->name('dashboard');
    Route::get('/clientes/nuevo', ClienteWizard::class)->name('clientes.crear');
    Route::get('/clientes/{id}/pdf/ver', [ClientePdfController::class, 'ver'])->name('clientes.pdf.ver');
    Route::get('/clientes/{id}/pdf/descargar', [ClientePdfController::class, 'descargar'])->name('clientes.pdf.descargar');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
