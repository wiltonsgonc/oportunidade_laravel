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

    <!-- Lista de Vagas com Ações -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Vagas Cadastradas
                </h5>
                <div>
                    <a href="{{ route('vagas.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Nova Vaga
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($ultimasVagas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Edital</th>
                                <th>Local</th>
                                <th>Setor</th>
                                <th>Status</th>
                                <th>Data Limite</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimasVagas as $vaga)
                            <tr>
                                <td class="ps-4">
                                    <strong>{{ Str::limit($vaga->edital, 40) }}</strong>
                                </td>
                                <td>
                                    {{ $vaga->tipo ?? '-' }}
                                </td>
                                <td>
                                    @if($vaga->setor == 'ÁREA TECNOLÓGICA SENAI CIMATEC')
                                        <span class="badge bg-info">Tecnológico</span>
                                    @elseif($vaga->setor == 'PRO-REITORIA DE GRADUAÇÃO')
                                        <span class="badge bg-primary">Graduação</span>
                                    @else
                                        <span class="badge bg-secondary">Pós/Pesquisa</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vaga->status == 'aberto')
                                        <span class="badge bg-success">Aberto</span>
                                    @else
                                        <span class="badge bg-danger">Encerrado</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $vaga->data_limite ? $vaga->data_limite->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Ações">
                                        <a href="{{ route('vagas.show', $vaga->id) }}" class="btn btn-outline-primary btn-sm" title="Visualizar">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('vagas.edit', $vaga->id) }}" class="btn btn-outline-warning btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('vagas.anexos', $vaga->id) }}" class="btn btn-outline-info btn-sm" title="Anexos">
                                            <i class="bi bi-paperclip"></i>
                                        </a>
                                        <a href="{{ route('vagas.retificacoes', $vaga->id) }}" class="btn btn-outline-primary btn-sm" title="Retificações">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </a>
                                        <a href="{{ route('vagas.destroy', $vaga->id) }}" 
                                           class="btn btn-outline-danger btn-sm btn-delete" 
                                           data-id="{{ $vaga->id }}" 
                                           data-edital="{{ $vaga->edital }}"
                                           onclick="event.preventDefault(); confirmarExclusao('{{ route('vagas.destroy', $vaga->id) }}', 'a vaga', '{{ addslashes($vaga->edital) }}');"
                                           title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($ultimasVagas->hasPages())
                <div class="card-footer bg-white border-top-0">
                    {{ $ultimasVagas->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-briefcase fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Nenhuma vaga cadastrada</h5>
                    <a href="{{ route('vagas.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Criar Primeira Vaga
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


