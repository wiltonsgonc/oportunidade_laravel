{{-- resources/views/vagas/retificacoes.blade.php --}}
@extends('layouts.app')

@section('title', 'Gerenciar Retificações')

@section('content')
<div class="container py-4" style="margin-top: 60px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Gerenciar Retificações
                    </h1>
                    <p class="text-muted mb-0">Vaga: {{ $vaga->edital }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>

            @if(!$podeEditar)
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                Você está visualizando as retificações em modo somente leitura. 
                Apenas o criador ou um administrador podem fazer alterações.
            </div>
            @endif

            <!-- Formulário de Upload -->
            @if($podeEditar)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Adicionar Nova Retificação</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Retificações adicionadas: <strong>{{ $vaga->retificacoes->count() }}/10</strong>
                    </p>
                    
                    @if($vaga->retificacoes->count() < 10)
                        <form id="form-upload-retificacao" data-vaga-id="{{ $vaga->id }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="retificacao" class="form-label">Arquivo</label>
                                        <input type="file" class="form-control" id="retificacao" name="retificacao" accept=".pdf,.doc,.docx,.odt" required>
                                        <div class="form-text">PDF, DOC, DOCX, ODT (Max: 10MB)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="500" placeholder="Breve descrição da retificação">
                                        <div class="form-text">Máximo 500 caracteres</div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i> Upload da Retificação
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Limite máximo de 10 retificações atingido. Exclua alguma retificação para adicionar novas.
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Lista de Retificações -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Retificações Existentes</h5>
                </div>
                <div class="card-body">
                    @if($vaga->retificacoes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Descrição</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vaga->retificacoes as $retificacao)
                                    <tr id="retificacao-{{ $retificacao->id }}">
                                        <td>
                                            <i class="bi bi-file-earmark-text text-primary"></i>
                                            {{ $retificacao->nome_original }}
                                        </td>
                                        <td>{{ $retificacao->descricao ?: '-' }}</td>
                                        <td>{{ $retificacao->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('vagas.download', ['tipo' => 'retificacao', 'id' => $retificacao->id]) }}" 
                                                   target="_blank" class="btn btn-outline-primary" title="Baixar">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-excluir-retificacao" 
                                                        onclick="excluirRetificacao({{ $retificacao->id }})" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-2">Nenhuma retificação adicionada ainda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/upload.js') }}"></script>
@endpush
