@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-people me-2"></i>Gerenciar Usuários
            </h1>
            <p class="text-muted mb-0">Cadastro e controle de usuários do sistema</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Novo Usuário
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.usuarios.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="busca" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busca" name="busca" 
                           placeholder="Nome, usuário ou email..." 
                           value="{{ request('busca') }}">
                </div>
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select class="form-select" id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Administradores</option>
                        <option value="ativo" {{ request('tipo') == 'ativo' ? 'selected' : '' }}>Ativos</option>
                        <option value="inativo" {{ request('tipo') == 'inativo' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Usuários -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        {{ $usuario->iniciais }}
                                    </div>
                                    <div>
                                        <strong>{{ $usuario->nome }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $usuario->usuario }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @if($usuario->is_admin_principal)
                                    <span class="badge bg-danger">Admin Principal</span>
                                @elseif($usuario->is_admin)
                                    <span class="badge bg-warning">Admin</span>
                                @else
                                    <span class="badge bg-secondary">Padrão</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $usuario->ativo ? 'success' : 'danger' }}">
                                    {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $usuario->created_at->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>

                                @php
                                    $isSelf = (auth()->id() === $usuario->id);
                                    // Verificar se é o primeiro admin principal
                                    $primeiroAdminPrincipalId = \App\Models\Usuario::where('is_admin_principal', true)
                                        ->orderBy('id', 'asc')
                                        ->value('id');
                                    $isPrimeiroAdminPrincipal = ($usuario->is_admin_principal && $usuario->id == $primeiroAdminPrincipalId);
                                @endphp

                                @if(!$isSelf && !$isPrimeiroAdminPrincipal)
                                    <button type="button" class="btn btn-sm btn-danger btn-excluir" 
                                            data-url="{{ route('admin.usuarios.destroy', $usuario->id) }}"
                                            data-tipo="o usuário"
                                            data-nome="{{ $usuario->nome }}">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                @elseif($isPrimeiroAdminPrincipal)
                                    <span class="badge bg-secondary">Protegido</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($usuarios->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $usuarios->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin.js') }}"></script>
@endpush
