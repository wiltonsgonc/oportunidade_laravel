<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\VagaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página inicial
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Rotas públicas de vagas
Route::get('/vagas', [VagaController::class, 'index'])->name('vagas.home');
Route::get('/vagas/{setor}', [VagaController::class, 'bySetor'])->name('vagas.setor');

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Alterar senha
    Route::get('/alterar-senha', function () {
        return view('auth.change-password');
    })->name('password.change');
});

// Para compatibilidade com links antigos
Route::redirect('/admin', '/dashboard');
Route::redirect('/admin/login', '/login');