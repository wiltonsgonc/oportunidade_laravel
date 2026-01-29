<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\Admin\AuthController;

// Rota principal
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Rotas públicas de vagas
Route::prefix('vagas')->group(function () {
    Route::get('/home', [VagaController::class, 'home'])->name('vagas.home');
});

// Rotas de administração
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});