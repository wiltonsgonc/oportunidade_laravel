<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vaga;
use App\Models\VagaAnexo;
use App\Models\VagaRetificacao;

class VagaController extends Controller
{
    // Mapeamento dos setores
    private $setorMap = [
        'AREA_TECNOLOGICA' => 'ÁREA TECNOLÓGICA SENAI CIMATEC',
        'POS_PESQUISA' => 'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA',
        'GRADUACAO' => 'PRO-REITORIA DE GRADUAÇÃO',
    ];

    // Mapeamento dos filtros de imagem
    private $filtroClasses = [
        'AREA_TECNOLOGICA' => 'filtro-tecnologico',
        'POS_PESQUISA' => 'filtro-pos-pesquisa',
        'GRADUACAO' => 'filtro-graduacao',
        'default' => 'filtro-padrao'
    ];

    /**
     * Exibir página principal de vagas (substitui home.php)
     */
    public function home(Request $request)
    {
        $setorKey = $request->query('setor', '');
        $status = $request->query('status', 'aberto');
        
        // Valida status
        if (!in_array($status, ['aberto', 'encerrado'])) {
            $status = 'aberto';
        }

        // Determinar classe de filtro
        $filtroClasse = $this->filtroClasses['default'];
        
        if ($status === 'encerrado') {
            $filtroClasse = $this->filtroClasses['default'];
        } elseif (!empty($setorKey) && isset($this->filtroClasses[$setorKey])) {
            $filtroClasse = $this->filtroClasses[$setorKey];
        }

        // Contar vagas abertas e encerradas
        $totalAbertas = $this->countVagas('aberto', $setorKey);
        $totalEncerradas = $this->countVagas('encerrado', $setorKey);

        return view('public.vagas.home', [
            'setorKey' => $setorKey,
            'status' => $status,
            'filtroClasse' => $filtroClasse,
            'totalAbertas' => $totalAbertas,
            'totalEncerradas' => $totalEncerradas,
            'setorNome' => $this->getSetorNome($setorKey)
        ]);
    }

    /**
     * Exibir lista de vagas (substitui vagas.php)
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'aberto');
        $setorKey = $request->query('setor', '');
        $page = $request->query('page', 1);
        
        // Validações
        if (!in_array($status, ['aberto', 'encerrado'])) {
            $status = 'aberto';
        }
        
        $page = max(1, (int)$page);

        // Configurar paginação
        $vagasPorPagina = ($status == 'encerrado') ? 20 : 6;

        // Construir query
        $query = Vaga::where('status', $status);

        // Filtrar por setor se especificado
        if (!empty($setorKey) && isset($this->setorMap[$setorKey])) {
            $query->where('setor', $this->setorMap[$setorKey]);
        }

        // Obter total para paginação
        $totalVagas = $query->count();
        $totalPaginas = ceil($totalVagas / $vagasPorPagina);

        // Ajustar página se inválida
        if ($page > $totalPaginas && $totalPaginas > 0) {
            $page = $totalPaginas;
        }

        // Obter vagas paginadas
        $vagas = $query->orderBy('criado_em', 'desc')
                      ->skip(($page - 1) * $vagasPorPagina)
                      ->take($vagasPorPagina)
                      ->get();

        // Carregar relações necessárias
        foreach ($vagas as $vaga) {
            $vaga->load(['anexos', 'retificacoes']);
        }

        // Gerar URLs para paginação
        $urlParams = $request->except('page');
        
        return view('public.vagas.partials.lista', [
            'vagas' => $vagas,
            'status' => $status,
            'setorKey' => $setorKey,
            'paginaAtual' => $page,
            'totalPaginas' => $totalPaginas,
            'totalVagas' => $totalVagas,
            'urlParams' => $urlParams
        ]);
    }

    /**
     * Contar vagas por status e setor
     */
    private function countVagas($status, $setorKey = '')
    {
        $query = Vaga::where('status', $status);
        
        if (!empty($setorKey) && isset($this->setorMap[$setorKey])) {
            $query->where('setor', $this->setorMap[$setorKey]);
        }
        
        return $query->count();
    }

    /**
     * Obter nome do setor
     */
    private function getSetorNome($setorKey)
    {
        $setores = [
            'GRADUACAO' => 'Graduação e Extensão',
            'POS_PESQUISA' => 'Pós-Graduação e Pesquisa',
            'AREA_TECNOLOGICA' => 'Projetos de Inovação'
        ];
        
        return $setores[$setorKey] ?? '';
    }

    /**
     * Formatar data segura
     */
    public static function formatarDataSegura($dataString)
    {
        if (empty($dataString) || $dataString == '0000-00-00') {
            return 'N/A';
        }
        
        $timestamp = strtotime($dataString);
        return $timestamp !== false ? date('d/m/Y', $timestamp) : 'Data inválida';
    }

    /**
     * Formatar moeda para exibição
     */
    public static function formatarMoedaParaExibicao($valor)
    {
        $valor = trim($valor);
        
        // Se já tem R$, mantém
        if (strpos($valor, 'R$') !== false) {
            return $valor;
        }
        
        // Se é numérico, formata
        if (is_numeric($valor)) {
            return 'R$ ' . number_format($valor, 2, ',', '.');
        }
        
        return $valor;
    }

    /**
     * Gerar token de download
     */
    public static function gerarTokenDownload($id, $tipo)
    {
        $secretKeys = [
            'edital' => 'vagas_secret_key_edital',
            'resultados' => 'vagas_secret_key_resultados',
            'anexo' => 'vagas_secret_key_anexos',
            'retificacao' => 'vagas_secret_key_retificacoes'
        ];
        
        if (isset($secretKeys[$tipo])) {
            return hash_hmac('sha256', $id, $secretKeys[$tipo]);
        }
        
        return '';
    }

    /**
     * Extrair nome original do arquivo
     */
    public static function extrairNomeOriginal($nomeArquivo)
    {
        // Remove timestamps e hashs
        $nome = preg_replace('/_\d{10,}_[a-f0-9]{8,}/', '', $nomeArquivo);
        $nome = preg_replace('/^\d+_/', '', $nome);
        
        // Remove extensão temporariamente
        $extensao = pathinfo($nome, PATHINFO_EXTENSION);
        $nomeBase = pathinfo($nome, PATHINFO_FILENAME);
        
        // Limita o tamanho para exibição
        if (strlen($nomeBase) > 50) {
            $nomeBase = substr($nomeBase, 0, 47) . '...';
        }
        
        return $extensao ? $nomeBase . '.' . $extensao : $nomeBase;
    }
}