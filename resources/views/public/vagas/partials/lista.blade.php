@php
use App\Http\Controllers\Public\VagaController;
@endphp

@if($status == 'aberto')
    <!-- VISUALIZAÇÃO EM CARDS (Vagas Abertas) -->
    <h3 class="text-light mb-3">Vagas Abertas</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($vagas as $vaga)
            <div class="col">
                <div class="card h-100 border-{{ $vaga->status == 'aberto' ? 'primary' : 'secondary' }}">
                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            {{ $vaga->edital ?: 'Edital Desconhecido' }}
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            {{ $vaga->setor ?: 'Setor Desconhecido' }}
                        </h6>

                        <p class="card-text text-justify-custom">
                            {{ $vaga->descricao ?: 'Descrição não disponível.' }}
                        </p>

                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item"><strong>Programa/Curso/Área:</strong>
                                {{ $vaga->programa_curso_area ?: 'N/A' }}</li>
                            <li class="list-group-item"><strong>Data Limite:</strong>
                                {{ VagaController::formatarDataSegura($vaga->data_limite) }}
                            </li>
                            <li class="list-group-item"><strong>Vagas Abertas:</strong>
                                {{ $vaga->numero_de_vagas ?: 'N/A' }}</li>

                            <li class="list-group-item">
                                <strong>E-mail de Contato:</strong>
                                @if($vaga->email_responsavel)
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
                                        echo VagaController::formatarMoedaParaExibicao($taxa_inscricao);
                                    }
                                @endphp
                            </li>

                            <li class="list-group-item">
                                <strong>Mensalidade/Bolsa:</strong>
                                @php
                                    $valor_bolsa = $vaga->mensalidade_bolsa ?? '';
                                    $valor_lower = strtolower(trim($valor_bolsa));
                                    if ($valor_lower === 'ver descrição' || $valor_lower === 'ver descricao' || empty($valor_bolsa) || floatval($valor_bolsa) == 0) {
                                        echo 'Ver descrição';
                                    } else {
                                        echo VagaController::formatarMoedaParaExibicao($valor_bolsa);
                                    }
                                @endphp
                            </li>

                            <li class="list-group-item">
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $vaga->status == 'aberto' ? 'success' : 'danger' }}">
                                    {{ ucfirst($vaga->status ?: 'Encerrado') }}
                                </span>
                            </li>
                        </ul>

                        <!-- BOTÕES PRINCIPAIS -->
                        <div class="d-grid gap-2">
                            @if($vaga->arquivo_edital)
                                <a href="{{ route('download.edital', ['id' => $vaga->id, 'token' => VagaController::gerarTokenDownload($vaga->id, 'edital')]) }}"
                                    target="_blank" class="btn btn-secondary" 
                                    aria-label="Baixar edital {{ $vaga->edital }}">
                                    <i class="bi bi-file-earmark-text"></i> Edital
                                </a>
                            @endif

                            @if($vaga->arquivo_resultados)
                                <a href="{{ route('download.resultados', ['id' => $vaga->id, 'token' => VagaController::gerarTokenDownload($vaga->id, 'resultados')]) }}"
                                    target="_blank" class="btn btn-outline-success" 
                                    aria-label="Baixar resultados {{ $vaga->edital }}">
                                    <i class="bi bi-download"></i> Resultados
                                </a>
                            @endif

                            @if($vaga->status == 'aberto' && $vaga->link_inscricao)
                                <a href="{{ $vaga->link_inscricao }}" target="_blank"
                                    class="btn btn-success" 
                                    aria-label="Inscrever-se na vaga {{ $vaga->edital }}">
                                    <i class="bi bi-pencil-square"></i> Inscreva-se
                                </a>
                            @endif
                        </div>

                        <!-- ANEXOS -->
                        @if($vaga->anexos && $vaga->anexos->count() > 0)
                            <div class="mt-3 pt-3 border-top">
                                <h6 class="text-muted mb-2"><i class="bi bi-paperclip"></i> Anexos:</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($vaga->anexos as $anexo)
                                        <a href="{{ route('download.anexo', ['id' => $anexo->id, 'token' => VagaController::gerarTokenDownload($anexo->id, 'anexo')]) }}" 
                                           target="_blank" class="list-group-item list-group-item-action py-2" 
                                           aria-label="Baixar anexo {{ $anexo->nome_original ?: VagaController::extrairNomeOriginal($anexo->nome_arquivo) }}">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <small class="text-truncate">
                                                    <i class="bi bi-file-earmark me-2"></i>
                                                    {{ $anexo->nome_original ?: VagaController::extrairNomeOriginal($anexo->nome_arquivo) }}
                                                </small>
                                                <i class="bi bi-download text-muted"></i>
                                            </div>
                                            @if($anexo->descricao)
                                                <small class="text-muted d-block mt-1">{{ $anexo->descricao }}</small>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- RETIFICAÇÕES -->
                        @if($vaga->retificacoes && $vaga->retificacoes->count() > 0)
                            <div class="mt-3 pt-3 border-top">
                                <h6 class="text-muted mb-2"><i class="bi bi-file-earmark-text"></i> Retificações:</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($vaga->retificacoes as $retificacao)
                                        <a href="{{ route('download.retificacao', ['id' => $retificacao->id, 'token' => VagaController::gerarTokenDownload($retificacao->id, 'retificacao')]) }}" 
                                           target="_blank" class="list-group-item list-group-item-action py-2" 
                                           aria-label="Baixar retificação {{ $retificacao->nome_original ?: VagaController::extrairNomeOriginal($retificacao->nome_arquivo) }}">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <small class="text-truncate">
                                                    <i class="bi bi-file-earmark-text me-2 text-muted"></i>
                                                    {{ $retificacao->nome_original ?: VagaController::extrairNomeOriginal($retificacao->nome_arquivo) }}
                                                </small>
                                                <i class="bi bi-download text-muted"></i>
                                            </div>
                                            @if($retificacao->descricao)
                                                <small class="text-muted d-block mt-1">{{ $retificacao->descricao }}</small>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">Nenhuma vaga encontrada.</div>
            </div>
        @endforelse
    </div>
@else
    <!-- VISUALIZAÇÃO EM LISTA/TABELA (Vagas Encerradas) -->
    <h3 class="text-light mb-3">Vagas Encerradas</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle" role="grid" aria-label="Vagas encerradas">
            <thead class="table-danger">
                <tr>
                    <th scope="col">Edital</th>
                    <th scope="col">Setor</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Programa/Curso/Área</th>
                    <th scope="col">Data de Fechamento</th>
                    <th scope="col">Visualizar Edital</th>
                    <th scope="col">Retificações</th>
                    <th scope="col">Resultados</th>
                    <th scope="col">Anexos</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vagas as $vaga)
                    <tr>
                        <td>{{ $vaga->edital ?: 'N/A' }}</td>
                        <td>{{ $vaga->setor ?: 'N/A' }}</td>
                        <td>{{ $vaga->tipo ?: 'N/A' }}</td>
                        <td>{{ $vaga->programa_curso_area ?: 'N/A' }}</td>
                        <td>{{ VagaController::formatarDataSegura($vaga->data_limite) }}</td>
                        <td>
                            @if($vaga->arquivo_edital)
                                <a href="{{ route('download.edital', ['id' => $vaga->id, 'token' => VagaController::gerarTokenDownload($vaga->id, 'edital')]) }}"
                                    target="_blank" class="btn btn-sm btn-secondary" 
                                    aria-label="Baixar edital {{ $vaga->edital }}">
                                    <i class="bi bi-file-earmark-text"></i> Edital
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($vaga->retificacoes && $vaga->retificacoes->count() > 0)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-file-earmark-text"></i> Retificações ({{ $vaga->retificacoes->count() }})
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($vaga->retificacoes as $retificacao)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('download.retificacao', ['id' => $retificacao->id, 'token' => VagaController::gerarTokenDownload($retificacao->id, 'retificacao')]) }}" target="_blank">
                                                    <i class="bi bi-download me-2"></i>
                                                    {{ $retificacao->nome_original ?: VagaController::extrairNomeOriginal($retificacao->nome_arquivo) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($vaga->arquivo_resultados)
                                <a href="{{ route('download.resultados', ['id' => $vaga->id, 'token' => VagaController::gerarTokenDownload($vaga->id, 'resultados')]) }}"
                                    target="_blank" class="btn btn-sm btn-success" 
                                    aria-label="Baixar resultados {{ $vaga->edital }}">
                                    <i class="bi bi-download"></i> Resultados
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($vaga->anexos && $vaga->anexos->count() > 0)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-paperclip"></i> Anexos ({{ $vaga->anexos->count() }})
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($vaga->anexos as $anexo)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('download.anexo', ['id' => $anexo->id, 'token' => VagaController::gerarTokenDownload($anexo->id, 'anexo')]) }}" target="_blank">
                                                    <i class="bi bi-download me-2"></i>
                                                    {{ $anexo->nome_original ?: VagaController::extrairNomeOriginal($anexo->nome_arquivo) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Nenhuma vaga encerrada encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

<!-- PAGINAÇÃO -->
@if($totalPaginas > 1)
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Navegação de Vagas">
            <ul class="pagination">
                {{-- Link Anterior --}}
                @if($paginaAtual > 1)
                    @php
                        $urlAnterior = array_merge($urlParams, ['page' => $paginaAtual - 1]);
                    @endphp
                    <li class="page-item">
                        <a class="page-link" href="{{ route('vagas.index', $urlAnterior) }}" aria-label="Página anterior">
                            <span aria-hidden="true">&laquo;</span> Anterior
                        </a>
                    </li>
                @endif

                {{-- Números das páginas --}}
                @php
                    $maxLinks = 5;
                    $start = max(1, $paginaAtual - floor($maxLinks / 2));
                    $end = min($totalPaginas, $start + $maxLinks - 1);
                    
                    if ($end - $start + 1 < $maxLinks) {
                        $start = max(1, $end - $maxLinks + 1);
                    }
                @endphp

                @for ($i = $start; $i <= $end; $i++)
                    @php
                        $urlPagina = array_merge($urlParams, ['page' => $i]);
                    @endphp
                    <li class="page-item {{ $i == $paginaAtual ? 'active' : '' }}">
                        <a class="page-link" href="{{ route('vagas.index', $urlPagina) }}" 
                           aria-label="Página {{ $i }}" {{ $i == $paginaAtual ? 'aria-current="page"' : '' }}>
                            {{ $i }}
                        </a>
                    </li>
                @endfor

                {{-- Link Próxima --}}
                @if($paginaAtual < $totalPaginas)
                    @php
                        $urlProxima = array_merge($urlParams, ['page' => $paginaAtual + 1]);
                    @endphp
                    <li class="page-item">
                        <a class="page-link" href="{{ route('vagas.index', $urlProxima) }}" aria-label="Próxima página">
                            Próxima <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif