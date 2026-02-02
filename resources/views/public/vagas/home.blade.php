@extends('layouts.vagas')

@section('title', 'Vagas - ' . ($setorNome ?: 'Oportunidades SENAI CIMATEC'))

@section('content')
<div class="flex-grow {{ $filtroClasse }}">
    <div class="container my-5">
        <!-- Botões de status -->
        <div class="d-flex justify-content-center mb-4">
            <div class="btn-group" role="group">
                <a href="{{ url('/vagas?' . http_build_query(['setor' => $setor, 'status' => 'aberto'])) }}" 
                   class="btn btn-{{ request('status', 'aberto') === 'aberto' ? 'primary' : 'outline-primary' }}">
                    Vagas Abertas
                    @if($vagas->where('status', 'aberto')->count() > 0)
                        <span class="badge bg-light text-dark ms-1">{{ $vagas->where('status', 'aberto')->count() }}</span>
                    @endif
                </a>
                <a href="{{ url('/vagas?' . http_build_query(['setor' => $setor, 'status' => 'encerrado'])) }}" 
                   class="btn btn-{{ request('status') === 'encerrado' ? 'primary' : 'outline-primary' }}">
                    Vagas Encerradas
                    @if($vagas->where('status', 'encerrado')->count() > 0)
                        <span class="badge bg-light text-dark ms-1">{{ $vagas->where('status', 'encerrado')->count() }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Título -->
        <h2 class="text-light text-center mb-4">
            @if($setorNome)
                {{ $setorNome }}
            @else
                Todas as Vagas
            @endif
        </h2>

        <!-- Lista de Vagas -->
        @if($vagas->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($vagas as $vaga)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $vaga->edital }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $vaga->setor }}</h6>
                                <p class="card-text">{{ Str::limit($vaga->descricao, 150) }}</p>
                                
                                <ul class="list-unstyled">
                                    <li><strong>Data Limite:</strong> {{ \Carbon\Carbon::parse($vaga->data_limite)->format('d/m/Y') }}</li>
                                    <li><strong>Vagas:</strong> {{ $vaga->numero_de_vagas }}</li>
                                    <li><strong>Status:</strong> 
                                        <span class="badge bg-{{ $vaga->status === 'aberto' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($vaga->status) }}
                                        </span>
                                    </li>
                                </ul>
                                
                                <div class="mt-3">
                                    @if($vaga->arquivo_edital)
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> Edital
                                        </a>
                                    @endif
                                    
                                    @if($vaga->link_inscricao && $vaga->status === 'aberto')
                                        <a href="{{ $vaga->link_inscricao }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="bi bi-pencil-square"></i> Inscrever-se
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginação -->
            @if($vagas->hasPages())
                <div class="mt-4">
                    {{ $vagas->links() }}
                </div>
            @endif
        @else
            <div class="alert alert-info text-center">
                <h4>Nenhuma vaga encontrada</h4>
                <p>Não há vagas disponíveis no momento para esta categoria.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Voltar para página inicial</a>
            </div>
        @endif
    </div>
</div>
@endsection