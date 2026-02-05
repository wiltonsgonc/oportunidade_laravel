{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Oportunidades')

@section('content')
<div class="container mt-4">
    <!-- Cabeçalho do Dashboard -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </h1>
        <p class="dashboard-subtitle">Painel administrativo do sistema</p>
    </div>

    <!-- Mensagem de boas-vindas -->
    <div class="alert alert-success dashboard-welcome">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
            <div>
                <h6 class="mb-1">Bem-vindo ao Sistema de Oportunidades SENAI CIMATEC!</h6>
                <p class="mb-0">Sistema em desenvolvimento. Esta é a área administrativa.</p>
            </div>
        </div>
    </div>

    <!-- Cards de estatísticas -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-card-content">
                        <h6 class="stat-card-title">Usuários Ativos</h6>
                        <h3 class="stat-card-value">{{ $totalUsuarios }}</h3>
                        <small class="stat-card-subtitle">Usuários ativos no sistema</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="stat-card-content">
                        <h6 class="stat-card-title">Vagas Abertas</h6>
                        <h3 class="stat-card-value">{{ $vagasAbertas }}</h3>
                        <small class="stat-card-subtitle">Vagas disponíveis</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="stat-card-content">
                        <h6 class="stat-card-title">Vagas Encerradas</h6>
                        <h3 class="stat-card-value">{{ $vagasEncerradas }}</h3>
                        <small class="stat-card-subtitle">Vagas finalizadas</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card stat-card-info">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div class="stat-card-content">
                        <h6 class="stat-card-title">Total de Vagas</h6>
                        <h3 class="stat-card-value">{{ $totalVagas }}</h3>
                        <small class="stat-card-subtitle">Total cadastrado</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas Vagas -->
    @if($ultimasVagas->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>Últimas Vagas Cadastradas
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><i class="bi bi-card-heading me-1"></i> Título</th>
                            <th><i class="bi bi-gear me-1"></i> Status</th>
                            <th><i class="bi bi-calendar me-1"></i> Criado em</th>
                            <th><i class="bi bi-activity me-1"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimasVagas as $vaga)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($vaga->titulo ?? 'Sem título', 40) }}</strong>
                            </td>
                            <td>
                                @if($vaga->status == 'aberto')
                                    <span class="badge bg-success rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Aberto
                                    </span>
                                @elseif($vaga->status == 'encerrado')
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="bi bi-x-circle me-1"></i>Encerrado
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ $vaga->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $vaga->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $vaga->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-warning">
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
    <div class="card shadow-sm mb-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-briefcase dashboard-empty-icon"></i>
            <h5 class="mt-3 text-muted">Nenhuma vaga cadastrada</h5>
            <p class="text-muted">Comece cadastrando sua primeira vaga no sistema.</p>
            <a href="#" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Cadastrar Primeira Vaga
            </a>
        </div>
    </div>
    @endif

    <!-- Links Rápidos -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                <i class="bi bi-lightning-charge me-2"></i>Links Rápidos
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <a href="#" class="dashboard-quick-link dashboard-quick-link-primary">
                        <div class="dashboard-quick-link-icon">
                            <i class="bi bi-plus-circle"></i>
                        </div>
                        <div class="dashboard-quick-link-text">
                            <h6>Nova Vaga</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="{{ route('password.change') }}" class="dashboard-quick-link dashboard-quick-link-warning">
                        <div class="dashboard-quick-link-icon">
                            <i class="bi bi-key"></i>
                        </div>
                        <div class="dashboard-quick-link-text">
                            <h6>Alterar Senha</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="{{ route('home') }}" target="_blank" class="dashboard-quick-link dashboard-quick-link-secondary">
                        <div class="dashboard-quick-link-icon">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div class="dashboard-quick-link-text">
                            <h6>Site Público</h6>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <form method="POST" action="{{ route('logout') }}" class="h-100">
                        @csrf
                        <button type="submit" class="dashboard-quick-link dashboard-quick-link-danger">
                            <div class="dashboard-quick-link-icon">
                                <i class="bi bi-box-arrow-right"></i>
                            </div>
                            <div class="dashboard-quick-link-text">
                                <h6>Sair</h6>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Atualizar hora atual no dashboard
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            const dateString = now.toLocaleDateString('pt-BR');
            
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = `${dateString} ${timeString}`;
            }
        }
        
        updateTime();
        setInterval(updateTime, 60000);
        
        // Efeito hover nos cards
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