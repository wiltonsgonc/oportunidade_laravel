@extends('layouts.app')

@section('title', 'Auditoria do Sistema')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-shield-check me-2"></i>Auditoria do Sistema
            </h1>
            <p class="text-muted mb-0">Registro de atividades e gerenciamento de vagas arquivadas</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <ul class="nav nav-tabs" id="auditoriaTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $aba == 'auditoria' ? 'active' : '' }}" id="auditoria-tab"
                data-bs-toggle="tab" data-bs-target="#auditoria" type="button" role="tab">
                <i class="bi bi-shield-check me-1"></i> Auditoria de Ações
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $aba == 'arquivadas' ? 'active' : '' }}" id="arquivadas-tab"
                data-bs-toggle="tab" data-bs-target="#arquivadas" type="button" role="tab">
                <i class="bi bi-archive me-1"></i> Vagas Arquivadas
                @if($vagasArquivadas->isNotEmpty())
                    <span class="badge bg-warning ms-1">{{ $vagasArquivadas->count() }}</span>
                @endif
            </button>
        </li>
    </ul>

    <div class="tab-content" id="auditoriaTabsContent">
        <div class="tab-pane fade {{ $aba == 'auditoria' ? 'show active' : '' }}" id="auditoria" role="tabpanel">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Vaga</th>
                            <th>Usuário</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th>Edital</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditorias as $registro)
                            <tr>
                                <td>{{ $registro->vaga_id }}</td>
                                <td>
                                    {{ $registro->usuario_nome }}<br>
                                    <small class="text-muted">{{ $registro->usuario->email ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @php
                                        $badges = [
                                            'criado' => 'success',
                                            'atualizado' => 'info',
                                            'excluido' => 'warning',
                                            'restaurado' => 'primary',
                                            'excluido_permanentemente' => 'danger'
                                        ];
                                        $tipos = [
                                            'criado' => 'Criação',
                                            'atualizado' => 'Atualização',
                                            'excluido' => 'Arquivamento',
                                            'restaurado' => 'Restauração',
                                            'excluido_permanentemente' => 'Exclusão Permanente'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$registro->tipo_acao] ?? 'secondary' }}">
                                        {{ $tipos[$registro->tipo_acao] ?? $registro->tipo_acao }}
                                    </span>
                                </td>
                                <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $registro->dados_vaga['edital'] ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info"
                                        onclick="visualizarDetalhes({{ $registro->id }})">
                                        <i class="bi bi-eye"></i> Detalhes
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Nenhum registro de auditoria encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $auditorias->appends(['tab' => $aba])->links() }}
            </div>
        </div>

        <div class="tab-pane fade {{ $aba == 'arquivadas' ? 'show active' : '' }}" id="arquivadas" role="tabpanel">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>Edital</th>
                            <th>Setor</th>
                            <th>Programa/Curso/Área</th>
                            <th>Data Limite</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vagasArquivadas as $vaga)
                            <tr>
                                <td>{{ $vaga->id }}</td>
                                <td>{{ $vaga->edital ?? '' }}</td>
                                <td>{{ $vaga->setor }}</td>
                                <td>{{ $vaga->programa_curso_area }}</td>
                                <td>{{ $vaga->data_limite ? $vaga->data_limite->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="confirmarRestauracao({{ $vaga->id }}, '{{ addslashes($vaga->programa_curso_area) }}');">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmarExclusaoPermanente({{ $vaga->id }}, '{{ addslashes($vaga->programa_curso_area) }}');">
                                        <i class="bi bi-trash"></i> Excluir Permanentemente
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Nenhuma vaga arquivada encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetalhes" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Vaga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalhesConteudo">
            </div>
        </div>
    </div>
</div>

<form id="formRestaurar" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

<form id="formExcluirPermanente" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin.js') }}"></script>
@endpush
