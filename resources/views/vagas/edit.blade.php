{{-- resources/views/vagas/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Vaga')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-pencil me-2 text-warning"></i>Editar Vaga
                    </h1>
                    <p class="text-muted mb-0">Atualize os dados da vaga</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <!-- Alertas -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('vagas.update', $vaga->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Edital -->
                            <div class="col-md-6">
                                <label for="edital" class="form-label">
                                    Edital <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('edital') is-invalid @enderror" 
                                       id="edital" name="edital" value="{{ old('edital', $vaga->edital) }}" 
                                       maxlength="500" required>
                                <div class="form-text">Máximo 500 caracteres</div>
                                @error('edital')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Setor -->
                            <div class="col-md-6">
                                <label for="setor" class="form-label">
                                    Setor <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('setor') is-invalid @enderror" 
                                        id="setor" name="setor" required>
                                    <option value="" disabled>Selecione o Setor</option>
                                    <option value="PRO-REITORIA DE GRADUAÇÃO" {{ $vaga->setor == 'PRO-REITORIA DE GRADUAÇÃO' ? 'selected' : '' }}>
                                        PRO-REITORIA DE GRADUAÇÃO
                                    </option>
                                    <option value="PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA" {{ $vaga->setor == 'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA' ? 'selected' : '' }}>
                                        PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA
                                    </option>
                                    <option value="ÁREA TECNOLÓGICA SENAI CIMATEC" {{ $vaga->setor == 'ÁREA TECNOLÓGICA SENAI CIMATEC' ? 'selected' : '' }}>
                                        ÁREA TECNOLÓGICA SENAI CIMATEC
                                    </option>
                                </select>
                                @error('setor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Local/Tipo -->
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">
                                    Local <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('tipo') is-invalid @enderror" 
                                        id="tipo" name="tipo" required>
                                    <option value="" disabled>Selecione o Local</option>
                                    @if($vaga->setor == 'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA')
                                        <option value="STRICTO SENSU" {{ $vaga->tipo == 'STRICTO SENSU' ? 'selected' : '' }}>STRICTO SENSU</option>
                                        <option value="LATO SENSU" {{ $vaga->tipo == 'LATO SENSU' ? 'selected' : '' }}>LATO SENSU</option>
                                    @elseif($vaga->setor == 'ÁREA TECNOLÓGICA SENAI CIMATEC')
                                        <option value="PDI" {{ $vaga->tipo == 'PDI' ? 'selected' : '' }}>PDI</option>
                                    @endif
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Programa/Curso/Área -->
                            <div class="col-md-6">
                                <label for="programa_curso_area" class="form-label">
                                    Programa/Curso/Área <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('programa_curso_area') is-invalid @enderror" 
                                       id="programa_curso_area" name="programa_curso_area" 
                                       value="{{ old('programa_curso_area', $vaga->programa_curso_area) }}" maxlength="500" required>
                                <div class="form-text">Máximo 500 caracteres</div>
                                @error('programa_curso_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contato (Email) -->
                            <div class="col-md-6">
                                <label for="email_responsavel" class="form-label">
                                    Contato (Email) <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email_responsavel') is-invalid @enderror" 
                                       id="email_responsavel" name="email_responsavel" 
                                       value="{{ old('email_responsavel', $vaga->email_responsavel) }}" maxlength="255" required>
                                <div class="form-text">Máximo 255 caracteres</div>
                                @error('email_responsavel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Data Limite -->
                            <div class="col-md-6">
                                <label for="data_limite" class="form-label">
                                    Data Limite <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('data_limite') is-invalid @enderror" 
                                       id="data_limite" name="data_limite" value="{{ old('data_limite', $vaga->data_limite?->format('Y-m-d')) }}" required>
                                @error('data_limite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Número de Vagas -->
                            <div class="col-md-6">
                                <label for="numero_de_vagas" class="form-label">
                                    Número de Vagas <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('numero_de_vagas') is-invalid @enderror" 
                                       id="numero_de_vagas" name="numero_de_vagas" 
                                       value="{{ old('numero_de_vagas', $vaga->numero_de_vagas) }}" min="1" required>
                                @error('numero_de_vagas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Taxa de Inscrição -->
                            <div class="col-md-6">
                                <label for="taxa_inscricao" class="form-label">Taxa de Inscrição</label>
                                <input type="text" class="form-control @error('taxa_inscricao') is-invalid @enderror" 
                                       id="taxa_inscricao" name="taxa_inscricao" 
                                       value="{{ old('taxa_inscricao', $vaga->taxa_inscricao) }}" maxlength="100">
                                <div class="form-text">Deixe em branco para gratuito</div>
                                @error('taxa_inscricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mensalidade/Bolsa -->
                            <div class="col-md-6">
                                <label for="mensalidade_bolsa" class="form-label">Mensalidade/Bolsa</label>
                                <input type="text" class="form-control @error('mensalidade_bolsa') is-invalid @enderror" 
                                       id="mensalidade_bolsa" name="mensalidade_bolsa" 
                                       value="{{ old('mensalidade_bolsa', $vaga->mensalidade_bolsa) }}" maxlength="100">
                                <div class="form-text">Use vírgula para centavos</div>
                                @error('mensalidade_bolsa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="aberto" {{ $vaga->status == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                    <option value="encerrado" {{ $vaga->status == 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Arquivo do Edital -->
                            <div class="col-md-6">
                                <label for="arquivo_edital" class="form-label">Arquivo do Edital</label>
                                @if($vaga->nome_original_edital && $vaga->arquivo_edital && $vaga->arquivo_edital !== '0')
                                    <div class="mb-2 p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                                <span class="ms-2">{{ $vaga->nome_original_edital }}</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('vagas.download', ['tipo' => 'edital', 'id' => $vaga->id]) }}" 
                                                   target="_blank" class="btn btn-outline-primary" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmarExclusaoArquivo('{{ route('vagas.excluir-arquivo', ['id' => $vaga->id, 'tipo' => 'edital']) }}', 'edital')"
                                                        title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('arquivo_edital') is-invalid @enderror" 
                                       id="arquivo_edital" name="arquivo_edital" 
                                       accept=".pdf,.doc,.docx,.odt">
                                <div class="form-text">Deixe em branco para manter o atual. PDF, DOC, DOCX, ODT (Max: 10MB)</div>
                                @error('arquivo_edital')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Link de Inscrição -->
                            <div class="col-md-6">
                                <label for="link_inscricao" class="form-label">
                                    Link de Inscrição <span class="text-danger">*</span>
                                </label>
                                <input type="url" class="form-control @error('link_inscricao') is-invalid @enderror" 
                                       id="link_inscricao" name="link_inscricao" 
                                       value="{{ old('link_inscricao', $vaga->link_inscricao) }}" maxlength="512" required>
                                <div class="form-text">Máximo 512 caracteres</div>
                                @error('link_inscricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div class="col-12">
                                <label for="descricao" class="form-label">
                                    Descrição <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" name="descricao" rows="4" required>{{ old('descricao', $vaga->descricao) }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Botões -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-save me-1"></i> Atualizar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Seção de Anexos -->
                    <div class="mt-5">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-paperclip me-2"></i>Anexos
                        </h5>
                        
                        <!-- Formulário de Upload de Anexo -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bi bi-upload me-2"></i>Adicionar Novo Anexo</h6>
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
                                                    <label for="descricao_anexo" class="form-label">Descrição</label>
                                                    <input type="text" class="form-control" id="descricao_anexo" name="descricao" maxlength="500" placeholder="Breve descrição do arquivo">
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

                        <!-- Lista de Anexos Existentes -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Anexos Existentes</h6>
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const setorSelect = document.getElementById('setor');
    const tipoSelect = document.getElementById('tipo');
    
    const opcoesLocalMapa = {
        'PRO-REITORIA DE GRADUAÇÃO': [],
        'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA': ['STRICTO SENSU', 'LATO SENSU'],
        'ÁREA TECNOLÓGICA SENAI CIMATEC': ['PDI']
    };

    setorSelect.addEventListener('change', function() {
        const setorSelecionado = this.value;
        const tipoAtual = tipoSelect.value;
        
        tipoSelect.innerHTML = '<option value="" disabled>Selecione o Local</option>';
        
        const locais = opcoesLocalMapa[setorSelecionado] || [];
        
        locais.forEach(function(local) {
            const option = document.createElement('option');
            option.value = local;
            option.textContent = local;
            if (local === tipoAtual) {
                option.selected = true;
            }
            tipoSelect.appendChild(option);
        });
        
        if (locais.length === 0) {
            tipoSelect.innerHTML = '<option value="" selected disabled>Sem opções para este setor</option>';
        }
        
        tipoSelect.disabled = locais.length === 0;
    });

    // Upload de anexos via AJAX com barra de progresso
    const formUploadAnexo = document.getElementById('form-upload-anexo');
    if (formUploadAnexo) {
        formUploadAnexo.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const vagaId = {{ $vaga->id }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const submitBtn = formUploadAnexo.querySelector('button[type="submit"]');
            
            // Mostrar SweetAlert com barra de progresso
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
                        progressBar.setAttribute('aria-valuenow', percentComplete);
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

            xhr.open('POST', `/vagas/${vagaId}/anexo`);
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
                    document.getElementById(`anexo-${anexoId}`)?.remove();
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
