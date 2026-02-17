{{-- resources/views/vagas/excluir.blade.php --}}
@extends('layouts.app')

@section('title', 'Excluir Vagas')

@section('content')
<div class="container py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-trash me-2"></i>Excluir Vagas
            </h1>
            <p class="text-muted mb-0">Selecione uma vaga para excluir</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    @if($vagas->count() > 0)
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Edital</th>
                            <th>Setor</th>
                            <th>Status</th>
                            <th>Data Limite</th>
                            <th class="text-end pe-4">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vagas as $vaga)
                        <tr>
                            <td class="ps-4">
                                <strong>{{ Str::limit($vaga->edital, 35) }}</strong>
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
                                <a href="{{ route('vagas.destroy', $vaga->id) }}" 
                                   class="btn btn-sm btn-danger btn-delete"
                                   onclick="event.preventDefault(); confirmarExclusao('{{ route('vagas.destroy', $vaga->id) }}', 'a vaga', '{{ addslashes($vaga->edital) }}');">
                                    <i class="bi bi-trash me-1"></i> Excluir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($vagas->hasPages())
            <div class="card-footer bg-white border-top-0">
                {{ $vagas->links() }}
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-briefcase fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">Nenhuma vaga disponível para exclusão</h5>
            <p class="text-muted">Crie uma nova vaga para começar.</p>
            <a href="{{ route('vagas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Criar Nova Vaga
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
