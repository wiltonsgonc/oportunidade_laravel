<!-- public/vagas/home.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oportunidades - SENAI CIMATEC</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/ico">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bs-secondary-bg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/LOGOTI_2.png') }}" alt="UNIVERSIDADE SENAI CIMATEC" height="60"
                    class="d-inline-block align-top">
            </a>

            <!-- Texto centralizado -->
            <div class="navbar-text mx-auto d-none d-md-block">
                <span class="fs-4 text-primary fw-bold">
                    Conecte-se ao Futuro: Editais, Bolsas e Programas
                </span>
            </div>
        </div>
    </nav>

    <!-- Detectar a classe de filtro -->
    @php
    $setores_nomes = [
        'PRO-REITORIA DE GRADUAÇÃO' => 'Graduação e Extensão',
        'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA' => 'Pós-Graduação e Pesquisa',
        'ÁREA TECNOLÓGICA SENAI CIMATEC' => 'Projetos de Inovação',
    ];
    
    $filtro_classes = [
        'PRO-REITORIA DE GRADUAÇÃO' => 'filtro-graduacao',
        'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA' => 'filtro-pos-pesquisa',
        'ÁREA TECNOLÓGICA SENAI CIMATEC' => 'filtro-tecnologico',
    ];
    
    $filtro_classe = 'filtro-padrao';
    
    if (isset($setor) && isset($filtro_classes[$setor])) {
        $filtro_classe = $filtro_classes[$setor];
    }
    
    if (isset($status) && $status === 'encerrado') {
        $filtro_classe = 'filtro-padrao';
    }
    
    $setor_nome = $setores_nomes[$setor] ?? null;
    @endphp

    <!-- Conteúdo Principal com Classe de Filtro -->
    <div class="flex-grow {{ $filtro_classe }}">
        <div class="container mt-4">
            <!-- Linha de filtros (Botões de status + Dropdown de setor) -->
            <div class="row justify-content-center mb-4">
                <div class="col-auto">
                    <!-- Botões de status -->
                    <div class="btn-group" role="group">
                        @php
                        $current_params = request()->query();
                        unset($current_params['status']);
                        @endphp
                        
                        <a href="{{ route('vagas.index', array_merge($current_params, ['status' => 'aberto'])) }}" 
                           class="btn btn-{{ ($status ?? 'aberto') === 'aberto' ? 'success' : 'outline-success' }}">
                            Vagas Abertas
                        </a>
                        <a href="{{ route('vagas.index', array_merge($current_params, ['status' => 'encerrado'])) }}" 
                           class="btn btn-{{ ($status ?? '') === 'encerrado' ? 'danger' : 'outline-danger' }}">
                            Vagas Encerradas
                        </a>
                    </div>
                </div>
                
                <div class="col-auto">
                    <!-- Dropdown de setor -->
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $setor_nome ?: 'Filtrar por Setor' }}
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item {{ !isset($setor) ? 'active fw-bold' : '' }}" 
                                   href="{{ route('vagas.index', ['status' => $status ?? 'aberto']) }}">
                                    Todas as Vagas
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item {{ ($setor ?? '') == 'PRO-REITORIA DE GRADUAÇÃO' ? 'active fw-bold' : '' }}" 
                                   href="{{ route('vagas.index', ['setor' => 'PRO-REITORIA DE GRADUAÇÃO', 'status' => $status ?? 'aberto']) }}">
                                    Graduação e Extensão
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ ($setor ?? '') == 'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA' ? 'active fw-bold' : '' }}" 
                                   href="{{ route('vagas.index', ['setor' => 'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA', 'status' => $status ?? 'aberto']) }}">
                                    Pós-Graduação e Pesquisa
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ ($setor ?? '') == 'ÁREA TECNOLÓGICA SENAI CIMATEC' ? 'active fw-bold' : '' }}" 
                                   href="{{ route('vagas.index', ['setor' => 'ÁREA TECNOLÓGICA SENAI CIMATEC', 'status' => $status ?? 'aberto']) }}">
                                    Projetos de Inovação
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Listagem de vagas -->
            <div class="vagas-lista">
                @if($status === 'encerrado')
                    <!-- Tabela para vagas encerradas -->
                    <h3 class="text-light mb-3">Vagas Encerradas</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-danger">
                                <tr>
                                    <th>Edital</th>
                                    <th>Setor</th>
                                    <th>Tipo</th>
                                    <th>Programa/Curso/Área</th>
                                    <th>Data de Fechamento</th>
                                    <th>Edital</th>
                                    <th>Resultados</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vagas as $vaga)
                                <tr>
                                    <td>{{ $vaga->edital ?? 'N/A' }}</td>
                                    <td>{{ $vaga->setor ?? 'N/A' }}</td>
                                    <td>{{ $vaga->tipo ?? 'N/A' }}</td>
                                    <td>{{ $vaga->programa_curso_area ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($vaga->data_limite)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($vaga->nome_original_edital && $vaga->arquivo_edital && $vaga->arquivo_edital !== '0')
                                            <a href="{{ route('vagas.download', ['tipo' => 'edital', 'id' => $vaga->id]) }}" 
                                               target="_blank" class="btn btn-sm btn-secondary">
                                                <i class="bi bi-file-earmark-text"></i> Edital
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($vaga->nome_original_resultados && $vaga->arquivo_resultados && $vaga->arquivo_resultados !== '0')
                                            <a href="{{ route('vagas.download', ['tipo' => 'resultados', 'id' => $vaga->id]) }}" 
                                               target="_blank" class="btn btn-sm btn-success">
                                                <i class="bi bi-download"></i> Resultados
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Cards para vagas abertas -->
                    <h3 class="text-light mb-3">Vagas Abertas</h3>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @forelse($vagas as $vaga)
                        <div class="col">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        {{ $vaga->edital ?? 'Edital Desconhecido' }}
                                    </h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        {{ $vaga->setor ?? 'Setor Desconhecido' }}
                                    </h6>

                                    <p class="card-text text-justify-custom">
                                        {{ $vaga->descricao ?? 'Descrição não disponível.' }}
                                    </p>

                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item"><strong>Programa/Curso/Área:</strong>
                                            {{ $vaga->programa_curso_area ?? 'N/A' }}</li>
                                        <li class="list-group-item"><strong>Data Limite:</strong>
                                            {{ \Carbon\Carbon::parse($vaga->data_limite)->format('d/m/Y') }}
                                        </li>
                                        <li class="list-group-item"><strong>Vagas Abertas:</strong>
                                            {{ $vaga->numero_de_vagas ?? 'N/A' }}</li>
                                        <li class="list-group-item">
                                            <strong>E-mail de Contato:</strong>
                                            @if(!empty($vaga->email_responsavel))
                                                <a href="mailto:{{ $vaga->email_responsavel }}">
                                                    {{ $vaga->email_responsavel }}
                                                </a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Taxa de Inscrição:</strong>
                                            @php
                                                $taxa_inscricao = $vaga->taxa_inscricao ?? '';
                                                $taxa_valor = floatval(str_replace(',', '.', $taxa_inscricao));
                                                if (empty($taxa_inscricao) || $taxa_valor == 0 || $taxa_inscricao == 'Não se aplica') {
                                                    echo 'Gratuito';
                                                } else {
                                                    echo 'R$ ' . number_format($taxa_valor, 2, ',', '.');
                                                }
                                            @endphp
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Status:</strong>
                                            <span class="badge bg-{{ $vaga->status == 'aberto' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($vaga->status) }}
                                            </span>
                                        </li>
                                    </ul>

                                    <!-- Botões -->
                                    <div class="d-grid gap-2">
                                        @if($vaga->nome_original_edital && $vaga->arquivo_edital && $vaga->arquivo_edital !== '0')
                                            <a href="{{ route('vagas.download', ['tipo' => 'edital', 'id' => $vaga->id]) }}" 
                                               target="_blank" class="btn btn-secondary">
                                                <i class="bi bi-file-earmark-text"></i> Edital
                                            </a>
                                        @endif

                                        @if($vaga->nome_original_resultados && $vaga->arquivo_resultados && $vaga->arquivo_resultados !== '0')
                                            <a href="{{ route('vagas.download', ['tipo' => 'resultados', 'id' => $vaga->id]) }}" 
                                               target="_blank" class="btn btn-outline-success">
                                                <i class="bi bi-download"></i> Resultados
                                            </a>
                                        @endif

                                        @if($vaga->status == 'aberto' && !empty($vaga->link_inscricao))
                                            <a href="{{ $vaga->link_inscricao }}" target="_blank"
                                               class="btn btn-success">
                                                <i class="bi bi-pencil-square"></i> Inscreva-se
                                            </a>
                                        @endif

                                        <!-- Anexos -->
                                        @if($vaga->anexos && $vaga->anexos->count() > 0)
                                            <div class="mt-2">
                                                <small class="text-muted d-block mb-1">Anexo:</small>
                                                @if($vaga->anexos->count() == 1)
                                                    @php $anexo = $vaga->anexos->first(); @endphp
                                                    <a href="{{ route('vagas.download', ['tipo' => 'anexo', 'id' => $anexo->id]) }}" 
                                                       target="_blank" class="btn btn-sm btn-outline-secondary text-truncate d-inline-block" 
                                                       style="max-width: 100%;" title="{{ $anexo->nome_original }}">
                                                        <i class="bi bi-paperclip"></i> {{ Str::limit($anexo->nome_original, 30) }}
                                                    </a>
                                                @else
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-paperclip"></i> Anexos ({{ $vaga->anexos->count() }})
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @foreach($vaga->anexos as $anexo)
                                                                <li>
                                                                    <a class="dropdown-item d-flex justify-content-between align-items-center" 
                                                                       href="{{ route('vagas.download', ['tipo' => 'anexo', 'id' => $anexo->id]) }}" 
                                                                       target="_blank">
                                                                        <span class="text-truncate" style="max-width: 200px;" title="{{ $anexo->nome_original }}">
                                                                            <i class="bi bi-file-earmark me-1"></i>
                                                                            {{ Str::limit($anexo->nome_original, 25) }}
                                                                        </span>
                                                                        <i class="bi bi-download ms-2"></i>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <h4>Nenhuma vaga encontrada</h4>
                                <p>Não há vagas disponíveis no momento {{ $setor_nome ? "para $setor_nome" : '' }}.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Voltar para página inicial</a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                @endif

                <!-- Paginação -->
                @if($vagas->count() > 0)
                    <div class="d-flex justify-content-center mt-5">
                        {{ $vagas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">{{ date('Y') }} SENAI CIMATEC</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>