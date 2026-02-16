{{-- resources/views/vagas/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nova Vaga')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Nova Vaga
                    </h1>
                    <p class="text-muted mb-0">Preencha os dados da nova oportunidade</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <!-- Formulário -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('vagas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Título -->
                            <div class="col-12">
                                <label for="titulo" class="form-label">Título da Vaga *</label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                       id="titulo" name="titulo" value="{{ old('titulo') }}" 
                                       placeholder="Ex: Desenvolvedor Full Stack" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Descrição -->
                            <div class="col-12">
                                <label for="descricao" class="form-label">Descrição *</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" name="descricao" rows="5" required>{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Setor -->
                            <div class="col-md-6">
                                <label for="setor" class="form-label">Setor *</label>
                                <select class="form-select @error('setor') is-invalid @enderror" id="setor" name="setor" required>
                                    <option value="">Selecione...</option>
                                    <option value="GRADUACAO" {{ old('setor') == 'GRADUACAO' ? 'selected' : '' }}>Graduação e Extensão</option>
                                    <option value="POS_PESQUISA" {{ old('setor') == 'POS_PESQUISA' ? 'selected' : '' }}>Pós-Graduação e Pesquisa</option>
                                    <option value="AREA_TECNOLOGICA" {{ old('setor') == 'AREA_TECNOLOGICA' ? 'selected' : '' }}>Projetos de Inovação</option>
                                </select>
                                @error('setor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Selecione...</option>
                                    <option value="aberto" {{ old('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                    <option value="encerrado" {{ old('status') == 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Edital -->
                            <div class="col-md-6">
                                <label for="edital" class="form-label">Número do Edital</label>
                                <input type="text" class="form-control @error('edital') is-invalid @enderror" 
                                       id="edital" name="edital" value="{{ old('edital') }}" 
                                       placeholder="Ex: Edital 001/2024">
                                @error('edital')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Data Limite -->
                            <div class="col-md-6">
                                <label for="data_limite" class="form-label">Data Limite</label>
                                <input type="date" class="form-control @error('data_limite') is-invalid @enderror" 
                                       id="data_limite" name="data_limite" value="{{ old('data_limite') }}">
                                @error('data_limite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Remuneração -->
                            <div class="col-md-6">
                                <label for="remuneracao" class="form-label">Remuneração</label>
                                <input type="text" class="form-control @error('remuneracao') is-invalid @enderror" 
                                       id="remuneracao" name="remuneracao" value="{{ old('remuneracao') }}" 
                                       placeholder="Ex: R$ 2.500,00">
                                @error('remuneracao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Vagas Disponíveis -->
                            <div class="col-md-6">
                                <label for="vagas_disponiveis" class="form-label">Vagas Disponíveis</label>
                                <input type="number" class="form-control @error('vagas_disponiveis') is-invalid @enderror" 
                                       id="vagas_disponiveis" name="vagas_disponiveis" 
                                       value="{{ old('vagas_disponiveis') }}" min="0">
                                @error('vagas_disponiveis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Contato -->
                            <div class="col-12">
                                <label for="contato" class="form-label">Contato</label>
                                <input type="text" class="form-control @error('contato') is-invalid @enderror" 
                                       id="contato" name="contato" value="{{ old('contato') }}" 
                                       placeholder="Email ou telefone para contato">
                                @error('contato')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Requisitos -->
                            <div class="col-12">
                                <label for="requisitos" class="form-label">Requisitos</label>
                                <textarea class="form-control @error('requisitos') is-invalid @enderror" 
                                          id="requisitos" name="requisitos" rows="3">{{ old('requisitos') }}</textarea>
                                @error('requisitos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Arquivos -->
                            <div class="col-md-6">
                                <label for="arquivo_edital" class="form-label">Arquivo do Edital</label>
                                <input type="file" class="form-control @error('arquivo_edital') is-invalid @enderror" 
                                       id="arquivo_edital" name="arquivo_edital">
                                <div class="form-text">PDF, DOC, DOCX (máximo 5MB)</div>
                                @error('arquivo_edital')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="arquivo_resultados" class="form-label">Arquivo de Resultados</label>
                                <input type="file" class="form-control @error('arquivo_resultados') is-invalid @enderror" 
                                       id="arquivo_resultados" name="arquivo_resultados">
                                <div class="form-text">PDF, DOC, DOCX (máximo 5MB)</div>
                                @error('arquivo_resultados')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Botões -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Criar Vaga
                                    </button>
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