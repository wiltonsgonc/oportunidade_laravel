@extends('layouts.app')

@section('title', 'Criar Usuário')

@section('content')
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-person-plus me-2"></i>Novo Usuário
            </h1>
            <p class="text-muted mb-0">Cadastrar novo usuário no sistema</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.usuarios.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" name="nome" 
                                       value="{{ old('nome') }}" required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="usuario" class="form-label">Nome de Usuário *</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                                       id="usuario" name="usuario" 
                                       value="{{ old('usuario') }}" required>
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="senha" class="form-label">Senha *</label>
                                <input type="password" class="form-control @error('senha') is-invalid @enderror" 
                                       id="senha" name="senha" required>
                                @error('senha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="senha_confirmation" class="form-label">Confirmar Senha *</label>
                                <input type="password" class="form-control" 
                                       id="senha_confirmation" name="senha_confirmation" required>
                            </div>

                            @if(Auth::user()->is_admin_principal)
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3">Permissões</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_admin" name="is_admin" value="1"
                                           {{ old('is_admin') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_admin">
                                        Usuário Administrador (pode gerenciar o sistema)
                                    </label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_admin_principal" name="is_admin_principal" value="1"
                                           {{ old('is_admin_principal') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_admin_principal">
                                        Administrador Principal (acesso total)
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Salvar
                                </button>
                                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle me-1"></i>Informações
                    </h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-1"></i>Campos marcados com * são obrigatórios
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-1"></i>Senha deve ter no mínimo 6 caracteres
                        </li>
                        @if(!Auth::user()->is_admin_principal)
                        <li class="mb-0">
                            <i class="bi bi-lock text-warning me-1"></i>Apenas o admin principal pode criar outros administradores
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
