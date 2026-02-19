{{-- resources/views/vagas/anexos.blade.php --}}
@extends('layouts.app')

@section('title', 'Gerenciar Anexos')

@section('content')
<div class="container py-4" style="margin-top: 60px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-paperclip me-2"></i>Gerenciar Anexos
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
                Você está visualizando os anexos em modo somente leitura. 
                Apenas o criador ou um administrador podem fazer alterações.
            </div>
            @endif

            <!-- Formulário de Upload -->
            @if($podeEditar)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Adicionar Novo Anexo</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Anexos adicionados: <strong>{{ $vaga->anexos->count() }}/10</strong>
                    </p>
                    
                    @if($vaga->anexos->count() < 10)
                        <form id="form-upload-anexo" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="anexo" class="form-label">Arquivo</label>
                                        <input type="file" class="form-control" id="anexo" name="anexo" accept=".pdf,.doc,.docx,.odt" required>
                                        <div class="form-text">PDF, DOC, DOCX, ODT (Max: 10MB)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="descricao" name="descricao" maxlength="500" placeholder="Breve descrição do arquivo">
                                        <div class="form-text">Máximo 500 caracteres</div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-upload me-1"></i> Upload do Anexo
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Limite máximo de 10 anexos atingido. Exclua algum anexo para adicionar novos.
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Lista de Anexos -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Anexos Existentes</h5>
                </div>
                <div class="card-body">
                    @if($vaga->anexos->count() > 0)
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
                                    @foreach($vaga->anexos as $anexo)
                                    <tr id="anexo-{{ $anexo->id }}">
                                        <td>
                                            <i class="bi bi-file-earmark"></i>
                                            {{ $anexo->nome_original }}
                                        </td>
                                        <td>{{ $anexo->descricao ?: '-' }}</td>
                                        <td>{{ $anexo->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('vagas.download', ['tipo' => 'anexo', 'id' => $anexo->id]) }}" 
                                                   target="_blank" class="btn btn-outline-primary" title="Baixar">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-excluir-anexo" 
                                                        onclick="excluirAnexo({{ $anexo->id }})" title="Excluir">
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
                            <p class="text-muted mt-2">Nenhum anexo adicionado ainda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formUploadAnexo = document.getElementById('form-upload-anexo');
    if (formUploadAnexo) {
        formUploadAnexo.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            let progressHtml = `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mb-2">Enviando arquivo...</p>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <p class="progress-text mt-2 text-muted">0%</p>
                </div>
            `;
            
            Swal.fire({
                title: 'Enviando arquivo...',
                html: progressHtml,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    const progressBar = document.querySelector('.progress-bar');
                    const progressText = document.querySelector('.progress-text');
                    if (progressBar) {
                        progressBar.style.width = percentComplete + '%';
                    }
                    if (progressText) {
                        progressText.textContent = percentComplete + '%';
                    }
                }
            });

            xhr.addEventListener('load', function() {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && data.success) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Anexo adicionado com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Erro!',
                            text: data.error || 'Erro ao fazer upload do anexo.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (e) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Erro ao processar resposta do servidor.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            xhr.addEventListener('error', function() {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao fazer upload do anexo.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });

            xhr.open('POST', `/vagas/{{ $vaga->id }}/anexo`);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.send(formData);
        });
    }
});

function excluirAnexo(anexoId) {
    const vagaId = {{ $vaga->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    Swal.fire({
        title: 'Confirmar Exclusão',
        text: 'Tem certeza que deseja excluir este anexo? Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/vagas/${vagaId}/anexo/${anexoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: data.message || 'Anexo excluído com sucesso!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: data.error || 'Erro ao excluir anexo.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao excluir anexo.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}
</script>
@endpush
