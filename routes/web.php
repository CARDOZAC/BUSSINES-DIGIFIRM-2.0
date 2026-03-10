<?php

use App\Http\Controllers\ClienteDocumentoController;
use App\Http\Controllers\ClientePdfController;
use App\Http\Controllers\ProfileController;
use App\Livewire\ClienteWizard;
use App\Livewire\Admin\AuditoriaIndex;
use App\Livewire\Admin\UsuarioIndex;
use App\Livewire\Dashboard\DashboardHome;
use App\Livewire\Dashboard\TablaClientes;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/logos/{filename}', function (string $filename) {
    $allowed = ['AJAR_DISTRIBUCIONES-removebg-preview.png', 'RINVAL_SAS-removebg-preview.png', 'LOGODISTMASIVOS.-removebg-preview.png'];
    if (!in_array($filename, $allowed)) {
        abort(404);
    }
    $path = base_path('index/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    $mime = match (pathinfo($filename, PATHINFO_EXTENSION)) {
        'png' => 'image/png',
        'jfif', 'jpg', 'jpeg' => 'image/jpeg',
        default => 'application/octet-stream',
    };
    return response()->file($path, ['Content-Type' => $mime]);
})->name('logos.serve');

Route::middleware(['auth', 'verified', 'empresa.access'])->group(function () {
    Route::get('/dashboard', DashboardHome::class)->name('dashboard');
    Route::get('/clientes', TablaClientes::class)->name('clientes.index');
    Route::get('/clientes/nuevo', ClienteWizard::class)->name('clientes.crear');
    Route::get('/clientes/{id}/pdf/ver', [ClientePdfController::class, 'ver'])->name('clientes.pdf.ver');
    Route::get('/clientes/{id}/pdf/descargar', [ClientePdfController::class, 'descargar'])->name('clientes.pdf.descargar');
    Route::get('/clientes/{id}/documento/{tipo}', [ClienteDocumentoController::class, 'descargar'])->name('clientes.documento.descargar')->where('tipo', 'cedula|rut');

    Route::middleware('role:admin-cartera|super_admin')->group(function () {
        Route::get('/admin/auditoria', AuditoriaIndex::class)->name('admin.auditoria');
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/usuarios', UsuarioIndex::class)->name('admin.usuarios');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
