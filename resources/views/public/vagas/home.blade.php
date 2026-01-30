@extends('layouts.vagas')

@section('title', 'Vagas - ' . ($setorNome ?: 'Oportunidades SENAI CIMATEC'))

@section('content')
<!-- DEBUG: Mostrar qual classe está sendo aplicada -->
<div style="display: none;">Classe aplicada: {{ $filtroClasse }}</div>

<div class="flex-grow {{ $filtroClasse }}">
    <div class="container mt-4">
        <!-- Botões de status -->
        <div class="d-flex justify-content-center mb-4">
            <div class="btn-group" role="group">
                @php
                    // Monta os parâmetros atuais (mantendo o setor, se houver)
                    $paramsAbertas = request()->except('status');
                    $paramsAbertas['status'] = 'aberto';
                    
                    $paramsEncerradas = request()->except('status');
                    $paramsEncerradas['status'] = 'encerrado';
                @endphp
                
                <a href="{{ route('vagas.index', $paramsAbertas) }}" class="btn btn-success">
                    Abertas @if($totalAbertas > 0) <span class="badge bg-light text-dark ms-1">{{ $totalAbertas }}</span> @endif
                </a>
                <a href="{{ route('vagas.index', $paramsEncerradas) }}" class="btn btn-danger">
                    Encerradas @if($totalEncerradas > 0) <span class="badge bg-light text-dark ms-1">{{ $totalEncerradas }}</span> @endif
                </a>
            </div>
        </div>

        <!-- Lista de Vagas -->
        <div id="lista-vagas">
            @include('public.vagas.partials.lista', [
                'vagas' => [],
                'status' => $status,
                'setorKey' => $setorKey,
                'paginaAtual' => 1,
                'totalPaginas' => 0,
                'totalVagas' => 0,
                'urlParams' => request()->except('page')
            ])
        </div>
    </div>
</div>
@endsection