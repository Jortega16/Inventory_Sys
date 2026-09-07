<?php

use App\Http\Controllers\TenantOnboardingController;
use Illuminate\Support\Facades\Route;

// Estas rutas viven SOLO en el dominio central (localhost), nunca en un
// subdominio de tenant — de lo contrario colisionarían con routes/tenant.php.
Route::domain('localhost')->group(function () {
    Route::get('/', function () {
        return view('landing');
    })->name('home');

    Route::get('/registro', [TenantOnboardingController::class, 'showRegister'])->name('tenant.register');
    Route::post('/registro', [TenantOnboardingController::class, 'register'])->name('tenant.register.store');

    Route::get('/iniciar-sesion', [TenantOnboardingController::class, 'showLogin'])->name('workspace.find');
    Route::post('/iniciar-sesion', [TenantOnboardingController::class, 'login'])->name('workspace.find.store');
});
