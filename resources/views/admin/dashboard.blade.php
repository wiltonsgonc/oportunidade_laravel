@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container-fluid py-4">
    <!-- Cabeçalho Admin -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-shield-lock me-2"></i>Dashboard Administrativo
            </h1>
            <p class="text-muted mb-0">Painel de controle do sistema</p>
        </div>
        <div class="d-flex gap-2">
            @if(Auth::user()->is_admin_principal)
            <a href="{{ route('admin.auditoria.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-shield-check me-1"></i> Auditoria
            </a>
            @endif
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-people me-1"></i> Gerenciar Usuários
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Estatísticas Principais -->
    <div class="row g-4 mb-4">
        <!-- Usuários -->
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Total Usuários</h6>
                            <h2 class="mb-0">{{ $estatisticasUsuarios['total'] }}</h2>
                        </div>
                        <div class="avatar-sm bg-primary text-white rounded-circle p-2">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">Ativos: {{ $estatisticasUsuarios['ativos'] }}</span>
                        <span class="badge bg-danger ms-1">Inativos: {{ $estatisticasUsuarios['inativos'] }}</span>
                        <span class="badge bg-info ms-1">Admins: {{ $estatisticasUsuarios['admins'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vagas -->
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Total Vagas</h6>
                            <h2 class="mb-0">{{ $estatisticasVagas['total'] }}</h2>
                        </div>
                        <div class="avatar-sm bg-success text-white rounded-circle p-2">
                            <i class="bi bi-briefcase fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">Abertas: {{ $estatisticasVagas['abertas'] }}</span>
                        <span class="badge bg-secondary ms-1">Encerradas: {{ $estatisticasVagas['encerradas'] }}</span>
                        <span class="badge bg-warning ms-1">Excluídas: {{ $estatisticasVagas['excluidas'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arquivos -->
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Arquivos</h6>
                            <h2 class="mb-0">{{ $estatisticasVagas['com_edital'] + $estatisticasVagas['com_resultados'] }}</h2>
                        </div>
                        <div class="avatar-sm bg-info text-white rounded-circle p-2">
                            <i class="bi bi-files fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-primary">Editais: {{ $estatisticasVagas['com_edital'] }}</span>
                        <span class="badge bg-success ms-1">Resultados: {{ $estatisticasVagas['com_resultados'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividade -->
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted">Atividade</h6>
                            <h2 class="mb-0">{{ $estatisticasVagas['novas_semana'] }}</h2>
                        </div>
                        <div class="avatar-sm bg-warning text-white rounded-circle p-2">
                            <i class="bi bi-activity fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-primary">Última Semana</span>
                        <span class="badge bg-info ms-1">Este Mês: {{ $estatisticasVagas['novas_mes'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Usuários -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>Top Usuários (Vagas Criadas)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuário</th>
                                    <th>Vagas Abertas</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topUsuarios as $index => $usuario)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                {{ $usuario->iniciais }}
                                            </div>
                                            <div>
                                                <div>{{ $usuario->nome }}</div>
                                                <small class="text-muted">{{ $usuario->usuario }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $usuario->vagas_criadas_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->is_admin ? 'danger' : 'secondary' }}">
                                            {{ $usuario->tipo_usuario }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->ativo ? 'success' : 'danger' }}">
                                            {{ $usuario->status_formatado }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vagas por Setor -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Vagas por Setor (Detalhado)
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($vagasPorSetorDetalhado as $setor => $dados)
                    <div class="mb-3">
                        <h6 class="mb-2">
                            @if($setor == 'GRADUACAO')
                                <i class="bi bi-mortarboard text-primary me-1"></i>Graduação
                            @elseif($setor == 'POS_PESQUISA')
                                <i class="bi bi-book text-info me-1"></i>Pós/Pesquisa
                            @else
                                <i class="bi bi-cpu text-success me-1"></i>Tecnológico
                            @endif
                        </h6>
                        <div class="progress mb-1" style="height: 20px;">
                            @php
                                $totalSetor = $dados->sum('total');
                                $abertas = $dados->where('status', 'aberto')->first()->total ?? 0;
                                $encerradas = $dados->where('status', 'encerrado')->first()->total ?? 0;
                                $percentAbertas = $totalSetor > 0 ? ($abertas / $totalSetor) * 100 : 0;
                                $percentEncerradas = $totalSetor > 0 ? ($encerradas / $totalSetor) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $percentAbertas }}%">
                                {{ $abertas }}
                            </div>
                            <div class="progress-bar bg-secondary" style="width: {{ $percentEncerradas }}%">
                                {{ $encerradas }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Abertas: {{ $abertas }}</span>
                            <span>Total: {{ $totalSetor }}</span>
                            <span>Encerradas: {{ $encerradas }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Atividade do Sistema -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-activity me-2"></i>Atividade Recente
                    </h6>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($atividadeSistema as $log)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-{{ $log->nivel_cor }} me-2">
                                            {{ $log->nivel_formatado }}
                                        </span>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="mb-1">{{ $log->mensagem }}</div>
                                    @if($log->usuario)
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>{{ $log->usuario->nome }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Vagas que Precisam de Atenção -->
        <div class="col-lg-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>Precisa de Atenção
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-warning">
                                <tr>
                                    <th>Vaga</th>
                                    <th>Data Limite</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vagasAtencao as $vaga)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($vaga->titulo, 30) }}</div>
                                        <small class="text-muted">{{ $vaga->usuario->nome ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if($vaga->data_limite)
                                            @if($vaga->data_limite < now())
                                                <span class="badge bg-danger">Expirada</span>
                                            @else
                                                <span class="badge bg-warning">{{ $vaga->data_limite->format('d/m/Y') }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Sem data</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $vaga->status == 'aberto' ? 'success' : 'secondary' }}">
                                            {{ $vaga->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('vagas.edit', $vaga->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .progress-bar {
        font-size: 12px;
        line-height: 20px;
    }
</style>
@endpush