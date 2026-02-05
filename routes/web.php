<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Página inicial
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Página inicial (alias)
Route::get('/home', function () {
    return view('welcome');
})->name('home');

// Rotas de vagas públicas
Route::prefix('vagas')->group(function () {
    Route::get('/', [VagaController::class, 'index'])->name('vagas.index');
    Route::get('/setor/{setor}', [VagaController::class, 'bySetor'])->name('vagas.bySetor');
    Route::get('/download/{tipo}/{id}', [VagaController::class, 'download'])->name('vagas.download');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Alteração de senha
    Route::get('/password/change', function () {
        return view('auth.change-password');
    })->name('password.change');
    
    // Rotas administrativas para vagas
    Route::prefix('admin/vagas')->group(function () {
        Route::post('/{id}/restaurar', [VagaController::class, 'restore'])
            ->name('admin.vagas.restore');
        Route::delete('/{id}/force-delete', [VagaController::class, 'forceDelete'])
            ->name('admin.vagas.forceDelete');
    });
});