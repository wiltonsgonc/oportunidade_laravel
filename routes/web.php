<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Public\VagaController;
use App\Http\Controllers\Public\DownloadController;
use App\Http\Controllers\Admin\AuthController;

// Rota principal
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Rotas públicas de vagas
Route::prefix('vagas')->name('vagas.')->group(function () {
    // Página principal de vagas (home.php)
    Route::get('/home', [VagaController::class, 'home'])->name('home');
    
    // Lista de vagas (vagas.php) - esta é a rota principal
    Route::get('/', [VagaController::class, 'index'])->name('index');
});

// Rotas de download
Route::prefix('download')->name('download.')->group(function () {
    Route::get('/edital/{id}/{token}', [DownloadController::class, 'edital'])->name('edital');
    Route::get('/resultados/{id}/{token}', [DownloadController::class, 'resultados'])->name('resultados');
    Route::get('/anexo/{id}/{token}', [DownloadController::class, 'anexo'])->name('anexo');
    Route::get('/retificacao/{id}/{token}', [DownloadController::class, 'retificacao'])->name('retificacao');
});

// Rotas de administração (placeholder)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');
    
    // Para evitar erro, crie uma rota temporária
    // Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Route::post('/login', [AuthController::class, 'login']);
});