@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square me-2"></i>Editar Usuário
            </h1>
            <p class="text-muted mb-0">Atualizar dados do usuário</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" name="nome" 
                                       value="{{ old('nome', $usuario->nome) }}" required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="usuario" class="form-label">Nome de Usuário *</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                                       id="usuario" name="usuario" 
                                       value="{{ old('usuario', $usuario->usuario) }}" required>
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email', $usuario->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="senha" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control @error('senha') is-invalid @enderror" 
                                       id="senha" name="senha" 
                                       placeholder="Deixe em branco para manter a atual">
                                @error('senha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <small class="text-muted">Mínimo 6 caracteres. Deixe em branco para manter a senha atual.</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="senha_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" 
                                       id="senha_confirmation" name="senha_confirmation">
                            </div>

                            @php
                                $isProtectedAdmin = ($usuario->email === 'admin@cimatec.com.br');
                                $isSelf = (auth()->id() === $usuario->id);
                                $canEditAdmin = auth()->user()->is_admin_principal;
                            @endphp

                            @if($canEditAdmin && !$isProtectedAdmin)
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3">Permissões</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_admin" name="is_admin" value="1"
                                           {{ old('is_admin', $usuario->is_admin) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_admin">
                                        Usuário Administrador (pode gerenciar o sistema)
                                    </label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_admin_principal" name="is_admin_principal" value="1"
                                           {{ old('is_admin_principal', $usuario->is_admin_principal) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_admin_principal">
                                        Administrador Principal (acesso total, não pode ser excluído)
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3">Status</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="ativo" name="ativo" value="1"
                                           {{ old('ativo', $usuario->ativo) ? 'checked' : '' }}
                                           {{ $isSelf ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="ativo">
                                        Usuário Ativo
                                    </label>
                                    @if($isSelf)
                                        <small class="d-block text-muted">Você não pode desativar seu próprio usuário.</small>
                                    @endif
                                </div>
                            </div>

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
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-person me-1"></i>Informações do Usuário
                    </h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <strong>ID:</strong> {{ $usuario->id }}
                        </li>
                        <li class="mb-2">
                            <strong>Tipo:</strong> 
                            @if($usuario->is_admin_principal)
                                <span class="badge bg-danger">Admin Principal</span>
                            @elseif($usuario->is_admin)
                                <span class="badge bg-warning">Admin</span>
                            @else
                                <span class="badge bg-secondary">Padrão</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $usuario->ativo ? 'success' : 'danger' }}">
                                {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </li>
                        <li class="mb-2">
                            <strong>Criado em:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}
                        </li>
                        @if($usuario->ultimo_login)
                        <li class="mb-0">
                            <strong>Último acesso:</strong> {{ $usuario->ultimo_login->format('d/m/Y H:i') }}
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            @if($isProtectedAdmin)
            <div class="card border-warning bg-warning-subtle">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="bi bi-shield-exclamation me-1"></i>Usuário Protegido
                    </h6>
                    <p class="mb-0 small">Este é o usuário administrador principal do sistema e não pode ser excluído.</p>
                </div>
            </div>
            @endif

            @if($isSelf)
            <div class="card border-info bg-info-subtle">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-info-circle me-1"></i>Seu Usuário
                    </h6>
                    <p class="mb-0 small">Você está editando seu próprio usuário. Por segurança, não é possível desativar sua própria conta.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
