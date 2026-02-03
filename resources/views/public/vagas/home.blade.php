<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas - SENAI CIMATEC</title>
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

            <!-- Botão do admin -->
            <div class="d-flex">
                <a href="{{ route('login') }}" target="_blank" class="btn btn-primary text-nowrap bi bi-person">
                    Área do Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Detectar a classe de filtro -->
    @php
    // Mapeamento de setores
    $setores_nomes = [
        'GRADUACAO' => 'Graduação e Extensão',
        'POS_PESQUISA' => 'Pós-Graduação e Pesquisa',
        'AREA_TECNOLOGICA' => 'Projetos de Inovação',
    ];
    
    // Classes de filtro
    $filtro_classes = [
        'GRADUACAO' => 'filtro-graduacao',
        'POS_PESQUISA' => 'filtro-pos-pesquisa',
        'AREA_TECNOLOGICA' => 'filtro-tecnologico',
    ];
    
    // Definir classe padrão
    $filtro_classe = 'filtro-padrao';
    
    // Verificar se tem setor
    if (isset($setor) && isset($filtro_classes[$setor])) {
        $filtro_classe = $filtro_classes[$setor];
    }
    
    // Se for vagas encerradas, usa padrão
    if (isset($status) && $status === 'encerrado') {
        $filtro_classe = 'filtro-padrao';
    }
    
    $setor_nome = $setores_nomes[$setor] ?? null;
    @endphp

    <!-- Conteúdo Principal com Classe de Filtro -->
    <div class="flex-grow {{ $filtro_classe }}">
        <div class="container mt-4">
            <!-- Botões de status -->
            <div class="d-flex justify-content-center mb-4">
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

            <!-- Botões de setor -->
            <div class="d-flex justify-content-center mb-4">
                <div class="btn-group" role="group">
                    <a href="{{ route('vagas.index', ['status' => $status ?? 'aberto']) }}" 
                       class="btn {{ !isset($setor) ? 'btn-primary' : 'btn-outline-primary' }}">
                        Todas as Vagas
                    </a>
                    <a href="{{ route('vagas.index', ['setor' => 'GRADUACAO', 'status' => $status ?? 'aberto']) }}" 
                       class="btn {{ ($setor ?? '') == 'GRADUACAO' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Graduação e Extensão
                    </a>
                    <a href="{{ route('vagas.index', ['setor' => 'POS_PESQUISA', 'status' => $status ?? 'aberto']) }}" 
                       class="btn {{ ($setor ?? '') == 'POS_PESQUISA' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Pós-Graduação e Pesquisa
                    </a>
                    <a href="{{ route('vagas.index', ['setor' => 'AREA_TECNOLOGICA', 'status' => $status ?? 'aberto']) }}" 
                       class="btn {{ ($setor ?? '') == 'AREA_TECNOLOGICA' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Projetos de Inovação
                    </a>
                </div>
            </div>

            <!-- Cabeçalho do setor -->
            @if($setor_nome)
            <div class="vaga-header mb-4">
                <h1 class="text-center text-primary">{{ $setor_nome }}</h1>
                <p class="text-center text-muted">
                    @if($setor == 'GRADUACAO')
                    Vagas para graduação, extensão e estágios
                    @elseif($setor == 'POS_PESQUISA')
                    Vagas para pós-graduação, mestrado, doutorado e pesquisas
                    @elseif($setor == 'AREA_TECNOLOGICA')
                    Vagas para projetos de inovação e tecnologia
                    @endif
                </p>
            </div>
            @endif

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
                                        @if($vaga->arquivo_edital)
                                            <a href="{{ route('vagas.download', ['tipo' => 'edital', 'id' => $vaga->id]) }}" 
                                               target="_blank" class="btn btn-sm btn-secondary">
                                                <i class="bi bi-file-earmark-text"></i> Edital
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($vaga->arquivo_resultados)
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
                                                if (empty($taxa_inscricao) || floatval($taxa_inscricao) == 0) {
                                                    echo 'Gratuito';
                                                } else {
                                                    echo 'R$ ' . number_format($taxa_inscricao, 2, ',', '.');
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
                                        @if(!empty($vaga->arquivo_edital))
                                            <a href="{{ route('vagas.download', ['tipo' => 'edital', 'id' => $vaga->id]) }}" 
                                               target="_blank" class="btn btn-secondary">
                                                <i class="bi bi-file-earmark-text"></i> Edital
                                            </a>
                                        @endif

                                        @if(!empty($vaga->arquivo_resultados))
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <h4>Nenhuma vaga encontrada</h4>
                                <p>Não há vagas disponíveis no momento para esta categoria.</p>
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