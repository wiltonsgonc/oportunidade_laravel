{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Oportunidades')

@section('content')
<div class="container mt-4">
    
    <!-- Cards de estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="stat-card-content">
                        <h6 class="stat-card-title">Vagas Abertas</h6>
                        <h3 class="stat-card-value">{{ $vagasAbertas }}</h3>
                        <small class="stat-card-subtitle">Disponíveis</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="stat-card-content">
                        <h6 class="stat-card-title">Vagas Encerradas</h6>
                        <h3 class="stat-card-value">{{ $vagasEncerradas }}</h3>
                        <small class="stat-card-subtitle">Finalizadas</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div class="stat-card-content">
                        <h6 class="stat-card-title">Total de Vagas</h6>
                        <h3 class="stat-card-value">{{ $totalVagas }}</h3>
                        <small class="stat-card-subtitle">Cadastradas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Área do Menu de ações -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-1">
        <h5 class="mb-0">
            <i class="bi bi-menu-button-wide me-2"></i>Menu de ações
        </h5>
    </div>
    <div class="card-body py-3">
        <div class="row g-3">
            <div class="col-md-4 d-grid">
                <a href="{{ route('vagas.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-1"></i> Nova Vaga
                </a>
            </div>
            <div class="col-md-4 d-grid">
                <a href="{{ route('vagas.para-editar') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-pencil me-1"></i> Editar Vaga
                </a>
            </div>
            <div class="col-md-4 d-grid">
                <a href="{{ route('vagas.para-excluir') }}" class="btn btn-danger btn-lg">
                    <i class="bi bi-trash me-1"></i> Excluir Vaga
                </a>
            </div>
        </div>
    </div>
</div>

    <!-- Últimas Vagas - SIMPLIFICADA -->
    @if($ultimasVagas->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock me-2"></i>Últimas Vagas
                </h5>
                <a href="{{ route('vagas.index') }}" class="btn btn-sm btn-outline-secondary">
                    Ver todas
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Título</th>
                            <th>Setor</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimasVagas as $vaga)
                        <tr>
                            <td class="ps-4">
                                <strong>{{ Str::limit($vaga->titulo, 35) }}</strong>
                            </td>
                            <td>
                                @if($vaga->setor == 'tecnologico')
                                    <span class="badge bg-info">Tecnológico</span>
                                @elseif($vaga->setor == 'graduacao')
                                    <span class="badge bg-primary">Graduação</span>
                                @else
                                    <span class="badge bg-secondary">Pós/Pesquisa</span>
                                @endif
                            </td>
                            <td>
                                @if($vaga->status == 'aberto')
                                    <span class="badge bg-success">Aberto</span>
                                @else
                                    <span class="badge bg-secondary">Encerrado</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('vagas.show', $vaga->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('vagas.edit', $vaga->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-briefcase dashboard-empty-icon"></i>
            <h5 class="mt-3 text-muted">Nenhuma vaga cadastrada</h5>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efeito hover nos cards de estatística
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush