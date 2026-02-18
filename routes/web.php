<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\AnexoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/home', function () {
    return view('welcome');
})->name('home');

// Vagas públicas
Route::prefix('vagas')->group(function () {
    Route::get('/', [VagaController::class, 'index'])->name('vagas.index');
    Route::get('/setor/{setor}', [VagaController::class, 'bySetor'])->name('vagas.bySetor');
    Route::get('/download/{tipo}/{id}', [VagaController::class, 'download'])->name('vagas.download');
});

// Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Alteração de senha
    Route::get('/password/change', function () {
        return view('auth.change-password');
    })->name('password.change');
    
    /*
    |--------------------------------------------------------------------------
    | CRUD de Vagas (Área do Usuário)
    |--------------------------------------------------------------------------
    */
    Route::prefix('vagas')->group(function () {
        // Criar
        Route::get('/create', [VagaController::class, 'create'])->name('vagas.create');
        Route::post('/', [VagaController::class, 'store'])->name('vagas.store');
        
        // Para ações do dashboard
        Route::get('/para-editar', [VagaController::class, 'paraEditar'])->name('vagas.para-editar');
        Route::get('/para-excluir', [VagaController::class, 'paraExcluir'])->name('vagas.para-excluir');
        
        // Página de anexos
        Route::get('/{id}/anexos', [VagaController::class, 'anexos'])->name('vagas.anexos');
        
        // Upload e exclusão de anexos (controlador separado)
        Route::post('/{id}/anexo', [AnexoController::class, 'upload'])->name('vagas.upload-anexo');
        Route::delete('/{id}/anexo/{anexoId}', [AnexoController::class, 'excluir'])->name('vagas.excluir-anexo');
        
        // Excluir arquivo (edital/resultados)
        Route::delete('/{id}/arquivo/{tipo}', [VagaController::class, 'excluirArquivo'])->name('vagas.excluir-arquivo');
        
        // Download (genérico para editais, resultados e anexos)
        Route::get('/download/{tipo}/{id}', [VagaController::class, 'download'])->name('vagas.download');
        
        // Visualizar
        Route::get('/{id}', [VagaController::class, 'show'])->name('vagas.show');
        
        // Editar, Atualizar e Excluir
        Route::get('/{id}/edit', [VagaController::class, 'edit'])->name('vagas.edit');
        Route::put('/{id}', [VagaController::class, 'update'])->name('vagas.update');
        Route::delete('/{id}', [VagaController::class, 'destroy'])->name('vagas.destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Área Administrativa
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // Vagas admin
        Route::prefix('vagas')->name('vagas.')->group(function () {
            Route::get('/', [VagaController::class, 'adminIndex'])->name('index');
            Route::get('/lixeira', [VagaController::class, 'trash'])->name('trash');
            Route::post('/{id}/restaurar', [VagaController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [VagaController::class, 'forceDelete'])->name('forceDelete');
        });
    });
});