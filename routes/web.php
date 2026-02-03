<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\Auth\LoginController;

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
    // Listar vagas com filtros
    Route::get('/', [VagaController::class, 'index'])->name('vagas.index');
    
    // Rotas de setor
    Route::get('/setor/{setor}', [VagaController::class, 'bySetor'])->name('vagas.bySetor');
    
    // Download de arquivos
    Route::get('/download/{tipo}/{id}', function ($tipo, $id) {
        return app(VagaController::class)->download($tipo, $id);
    })->name('vagas.download');
});

// Rotas de autenticação (Laravel Breeze padrão)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Rotas administrativas para vagas
    Route::prefix('admin/vagas')->group(function () {
        Route::post('/{id}/restaurar', [VagaController::class, 'restore'])
            ->name('admin.vagas.restore');
            
        Route::delete('/{id}/force-delete', [VagaController::class, 'forceDelete'])
            ->name('admin.vagas.forceDelete');
    });
    
    // Alteração de senha
    Route::get('/password/change', function () {
        return view('auth.change-password');
    })->name('password.change');
});