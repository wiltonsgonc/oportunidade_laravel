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

            @if(!$podeEditar)
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                Você está visualizando esta vaga em modo somente leitura. 
                Apenas o criador ou um administrador podem fazer alterações.
            </div>
            @endif

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
                    @php
                        $disabled = $podeEditar ? '' : 'disabled';
                        $campoClass = $podeEditar ? '' : 'bg-light';
                    @endphp
                    <form action="{{ route('vagas.update', $vaga->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Edital -->
                            <div class="col-md-6">
                                <label for="edital" class="form-label">
                                    Edital <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('edital') is-invalid @enderror {{ $campoClass }}" 
                                       id="edital" name="edital" value="{{ old('edital', $vaga->edital) }}" 
                                       maxlength="500" required {{ $disabled }}>
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
                                <select class="form-select @error('setor') is-invalid @enderror {{ $campoClass }}" 
                                        id="setor" name="setor" required {{ $disabled }}>
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
                                <select class="form-select @error('tipo') is-invalid @enderror {{ $campoClass }}" 
                                        id="tipo" name="tipo" required {{ $disabled }}>
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
                                <input type="text" class="form-control @error('programa_curso_area') is-invalid @enderror {{ $campoClass }}" 
                                       id="programa_curso_area" name="programa_curso_area" 
                                       value="{{ old('programa_curso_area', $vaga->programa_curso_area) }}" maxlength="500" required {{ $disabled }}>
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
                                <input type="email" class="form-control @error('email_responsavel') is-invalid @enderror {{ $campoClass }}" 
                                       id="email_responsavel" name="email_responsavel" 
                                       value="{{ old('email_responsavel', $vaga->email_responsavel) }}" maxlength="255" required {{ $disabled }}>
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
                                <input type="date" class="form-control @error('data_limite') is-invalid @enderror {{ $campoClass }}" 
                                       id="data_limite" name="data_limite" value="{{ old('data_limite', $vaga->data_limite?->format('Y-m-d')) }}" required {{ $disabled }}>
                                @error('data_limite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Número de Vagas -->
                            <div class="col-md-6">
                                <label for="numero_de_vagas" class="form-label">
                                    Número de Vagas <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('numero_de_vagas') is-invalid @enderror {{ $campoClass }}" 
                                       id="numero_de_vagas" name="numero_de_vagas" 
                                       value="{{ old('numero_de_vagas', $vaga->numero_de_vagas) }}" min="1" required {{ $disabled }}>
                                @error('numero_de_vagas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Taxa de Inscrição -->
                            <div class="col-md-6">
                                <label for="taxa_inscricao" class="form-label">Taxa de Inscrição</label>
                                <input type="text" class="form-control @error('taxa_inscricao') is-invalid @enderror {{ $campoClass }}" 
                                       id="taxa_inscricao" name="taxa_inscricao" 
                                       value="{{ old('taxa_inscricao', $vaga->taxa_inscricao) }}" maxlength="100" {{ $disabled }}>
                                <div class="form-text">Deixe em branco para gratuito</div>
                                @error('taxa_inscricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mensalidade/Bolsa -->
                            <div class="col-md-6">
                                <label for="mensalidade_bolsa" class="form-label">Mensalidade/Bolsa</label>
                                <input type="text" class="form-control @error('mensalidade_bolsa') is-invalid @enderror {{ $campoClass }}" 
                                       id="mensalidade_bolsa" name="mensalidade_bolsa" 
                                       value="{{ old('mensalidade_bolsa', $vaga->mensalidade_bolsa) }}" maxlength="100" {{ $disabled }}>
                                <div class="form-text">Use vírgula para centavos</div>
                                @error('mensalidade_bolsa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror {{ $campoClass }}" id="status" name="status" required {{ $disabled }}>
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
                                                @if($podeEditar)
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmarExclusaoArquivo('{{ route('vagas.excluir-arquivo', ['id' => $vaga->id, 'tipo' => 'edital']) }}', 'edital')"
                                                        title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($podeEditar)
                                <input type="file" class="form-control @error('arquivo_edital') is-invalid @enderror" 
                                       id="arquivo_edital" name="arquivo_edital" 
                                       accept=".pdf,.doc,.docx,.odt">
                                <div class="form-text">Deixe em branco para manter o atual. PDF, DOC, DOCX, ODT (Max: 10MB)</div>
                                @else
                                <p class="text-muted mb-0"><small>Arquivo não disponível para edição</small></p>
                                @endif
                                @error('arquivo_edital')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Arquivo de Resultados -->
                            <div class="col-md-6">
                                <label for="arquivo_resultados" class="form-label">Arquivo de Resultados</label>
                                @if($vaga->nome_original_resultados && $vaga->arquivo_resultados && $vaga->arquivo_resultados !== '0')
                                    <div class="mb-2 p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-file-earmark-pdf text-success fs-4"></i>
                                                <span class="ms-2">{{ $vaga->nome_original_resultados }}</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('vagas.download', ['tipo' => 'resultados', 'id' => $vaga->id]) }}" 
                                                   target="_blank" class="btn btn-outline-primary" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($podeEditar)
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmarExclusaoArquivo('{{ route('vagas.excluir-arquivo', ['id' => $vaga->id, 'tipo' => 'resultados']) }}', 'resultados')"
                                                        title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($podeEditar)
                                <input type="file" class="form-control @error('arquivo_resultados') is-invalid @enderror" 
                                       id="arquivo_resultados" name="arquivo_resultados" 
                                       accept=".pdf,.doc,.docx,.odt">
                                <div class="form-text">Deixe em branco para manter o atual. PDF, DOC, DOCX, ODT (Max: 10MB)</div>
                                @else
                                <p class="text-muted mb-0"><small>Arquivo não disponível para edição</small></p>
                                @endif
                                @error('arquivo_resultados')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Link de Inscrição -->
                            <div class="col-md-6">
                                <label for="link_inscricao" class="form-label">
                                    Link de Inscrição <span class="text-danger">*</span>
                                </label>
                                <input type="url" class="form-control @error('link_inscricao') is-invalid @enderror {{ $campoClass }}" 
                                       id="link_inscricao" name="link_inscricao" 
                                       value="{{ old('link_inscricao', $vaga->link_inscricao) }}" maxlength="512" required {{ $disabled }}>
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
                                <textarea class="form-control @error('descricao') is-invalid @enderror {{ $campoClass }}" 
                                          id="descricao" name="descricao" rows="4" required {{ $disabled }}>{{ old('descricao', $vaga->descricao) }}</textarea>
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
                                    @if($podeEditar)
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-save me-1"></i> Atualizar
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
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
    const setorSelect = document.getElementById('setor');
    const tipoSelect = document.getElementById('tipo');
    
    const opcoesLocalMapa = {
        'PRO-REITORIA DE GRADUAÇÃO': ['GRADUAÇÃO'],
        'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA': ['STRICTO SENSU', 'LATO SENSU'],
        'ÁREA TECNOLÓGICA SENAI CIMATEC': ['PDI']
    };

    function populateTipo(setor, selectedTipo = null) {
        tipoSelect.innerHTML = '<option value="" disabled>Selecione o Local</option>';
        
        const locais = opcoesLocalMapa[setor] || [];
        
        locais.forEach(function(local) {
            const option = document.createElement('option');
            option.value = local;
            option.textContent = local;
            if (local === selectedTipo) {
                option.selected = true;
            }
            tipoSelect.appendChild(option);
        });
        
        if (locais.length === 0) {
            tipoSelect.innerHTML = '<option value="" selected disabled>Sem opções para este setor</option>';
        }
        
        tipoSelect.disabled = locais.length === 0;
    }

    // Inicializar tipo com base no setor carregado
    const setorInicial = setorSelect.value;
    const tipoSelecionado = tipoSelect.value;
    if (setorInicial) {
        populateTipo(setorInicial, tipoSelecionado);
    }

    setorSelect.addEventListener('change', function() {
        populateTipo(this.value);
    });

    // Upload de anexos via AJAX com barra de progresso
});
</script>
@endpush
