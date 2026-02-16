<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Vaga;
use App\Models\SistemaLog;
use App\Models\Usuario;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard Principal - Estatísticas Gerais + do Usuário
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $dataAtual = Carbon::now();
        
        // Registrar log de acesso
        $this->registrarLogAcesso($usuario, $request);
        
        // ============ ESTATÍSTICAS GLOBAIS ============
        $estatisticasGlobais = [
            'total_vagas' => Vaga::count(),
            'vagas_abertas' => Vaga::where('status', 'aberto')->count(),
            'vagas_encerradas' => Vaga::where('status', 'encerrado')->count(),
            'vagas_semana' => Vaga::where('created_at', '>=', $dataAtual->copy()->subWeek())->count(),
            'vagas_mes' => Vaga::where('created_at', '>=', $dataAtual->copy()->subMonth())->count(),
        ];
        
        // Distribuição por setor
        $distribuicaoSetor = [
            'graduacao' => Vaga::where('setor', 'GRADUACAO')->count(),
            'pos_pesquisa' => Vaga::where('setor', 'POS_PESQUISA')->count(),
            'tecnologico' => Vaga::where('setor', 'AREA_TECNOLOGICA')->count(),
        ];
        
        // ============ ESTATÍSTICAS DO USUÁRIO ============
        $estatisticasUsuario = [
            'total_vagas' => Vaga::where('usuario_id', $usuario->id)->count(),
            'vagas_abertas' => Vaga::where('usuario_id', $usuario->id)
                                ->where('status', 'aberto')
                                ->count(),
            'vagas_encerradas' => Vaga::where('usuario_id', $usuario->id)
                                  ->where('status', 'encerrado')
                                  ->count(),
            'vagas_semana' => Vaga::where('usuario_id', $usuario->id)
                                ->where('created_at', '>=', $dataAtual->copy()->subWeek())
                                ->count(),
            'vagas_mes' => Vaga::where('usuario_id', $usuario->id)
                              ->where('created_at', '>=', $dataAtual->copy()->subMonth())
                              ->count(),
        ];
        
        // ============ DADOS PARA GRÁFICOS ============
        // Vagas por mês (últimos 6 meses)
        $vagasPorMes = $this->getVagasPorMes($usuario->is_admin ? null : $usuario->id);
        
        // Vagas por setor
        $vagasPorSetor = $this->getVagasPorSetor($usuario->is_admin ? null : $usuario->id);
        
        // ============ ÚLTIMAS VAGAS ============
        $queryUltimasVagas = Vaga::with('usuario');
        
        if (!$usuario->is_admin) {
            $queryUltimasVagas->where('usuario_id', $usuario->id);
        }
        
        $ultimasVagas = $queryUltimasVagas->orderBy('created_at', 'desc')
                                         ->take(10)
                                         ->get();
        
        // ============ VAGAS EXPIRANDO EM BREVE ============
        $vagasExpirando = Vaga::where('status', 'aberto')
                            ->where('data_limite', '>=', $dataAtual)
                            ->where('data_limite', '<=', $dataAtual->copy()->addDays(7))
                            ->orderBy('data_limite', 'asc')
                            ->take(5)
                            ->get();
        
        // ============ ATIVIDADE RECENTE ============
        $atividadeRecente = SistemaLog::orderBy('created_at', 'desc')
                                     ->take(10)
                                     ->get();

        $vagasAbertas = $estatisticasGlobais['vagas_abertas'];
        $vagasEncerradas = $estatisticasGlobais['vagas_encerradas'];
        $totalVagas = $estatisticasGlobais['total_vagas'];

        return view('dashboard.index', compact(
            'estatisticasGlobais',
            'estatisticasUsuario',
            'distribuicaoSetor',
            'vagasPorMes',
            'vagasPorSetor',
            'ultimasVagas',
            'vagasExpirando',
            'atividadeRecente',
            'usuario',
            'vagasAbertas',
            'vagasEncerradas',
            'totalVagas'
        ));
    }

    /**
     * Dashboard Administrativo
     */
    public function admin(Request $request)
    {
        $usuario = Auth::user();
        
        if (!$usuario->is_admin) {
            abort(403, 'Acesso não autorizado. Apenas administradores podem acessar esta área.');
        }
        
        $dataAtual = Carbon::now();
        
        // ============ ESTATÍSTICAS COMPLETAS PARA ADMIN ============
        // Estatísticas de Usuários
        $estatisticasUsuarios = [
            'total' => Usuario::count(),
            'ativos' => Usuario::where('ativo', true)->count(),
            'inativos' => Usuario::where('ativo', false)->count(),
            'admins' => Usuario::where('is_admin', true)->count(),
            'nao_admins' => Usuario::where('is_admin', false)->count(),
            'novos_semana' => Usuario::where('created_at', '>=', $dataAtual->copy()->subWeek())->count(),
            'novos_mes' => Usuario::where('created_at', '>=', $dataAtual->copy()->subMonth())->count(),
        ];
        
        // Estatísticas de Vagas
        $estatisticasVagas = [
            'total' => Vaga::count(),
            'abertas' => Vaga::where('status', 'aberto')->count(),
            'encerradas' => Vaga::where('status', 'encerrado')->count(),
            'com_edital' => Vaga::whereNotNull('arquivo_edital')->count(),
            'com_resultados' => Vaga::whereNotNull('arquivo_resultados')->count(),
            'novas_semana' => Vaga::where('created_at', '>=', $dataAtual->copy()->subWeek())->count(),
            'novas_mes' => Vaga::where('created_at', '>=', $dataAtual->copy()->subMonth())->count(),
            'excluidas' => Vaga::onlyTrashed()->count(),
        ];
        
        // Top usuários por vagas criadas
        $topUsuarios = Usuario::withCount(['vagasCriadas' => function($query) {
                $query->where('status', 'aberto');
            }])
            ->orderBy('vagas_criadas_count', 'desc')
            ->take(10)
            ->get();
        
        // Vagas por setor (detalhado)
        $vagasPorSetorDetalhado = Vaga::selectRaw('setor, status, COUNT(*) as total')
            ->groupBy('setor', 'status')
            ->orderBy('setor')
            ->get()
            ->groupBy('setor');
        
        // Atividade recente do sistema
        $atividadeSistema = SistemaLog::with('usuario')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        // Últimas vagas criadas
        $ultimasVagas = Vaga::with('usuario')
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();
        
        // Vagas que precisam de atenção
        $vagasAtencao = Vaga::where('status', 'aberto')
            ->where(function($query) use ($dataAtual) {
                $query->whereNull('data_limite')
                      ->orWhere('data_limite', '<=', $dataAtual);
            })
            ->orderBy('data_limite', 'asc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'estatisticasUsuarios',
            'estatisticasVagas',
            'topUsuarios',
            'vagasPorSetorDetalhado',
            'atividadeSistema',
            'ultimasVagas',
            'vagasAtencao',
            'usuario'
        ));
    }

    /**
     * Métodos Auxiliares
     */
    
    private function registrarLogAcesso($usuario, $request)
    {
        try {
            SistemaLog::create([
                'nivel' => 'info',
                'mensagem' => 'Acesso ao dashboard',
                'usuario_id' => $usuario->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'metodo' => $request->method(),
                'detalhes' => json_encode([
                    'usuario' => $usuario->usuario,
                    'is_admin' => $usuario->is_admin,
                    'pagina' => 'dashboard'
                ])
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao registrar log de acesso: ' . $e->getMessage());
        }
    }
    
    private function getVagasPorMes($usuarioId = null)
    {
        $dataAtual = Carbon::now();
        $vagasPorMes = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $mes = $dataAtual->copy()->subMonths($i);
            $inicioMes = $mes->copy()->startOfMonth();
            $fimMes = $mes->copy()->endOfMonth();
            
            $query = Vaga::whereBetween('created_at', [$inicioMes, $fimMes]);
            
            if ($usuarioId) {
                $query->where('usuario_id', $usuarioId);
            }
            
            $vagasPorMes[] = [
                'mes' => $mes->format('M/Y'),
                'total' => $query->count(),
                'abertas' => $query->clone()->where('status', 'aberto')->count(),
                'encerradas' => $query->clone()->where('status', 'encerrado')->count(),
            ];
        }
        
        return $vagasPorMes;
    }
    
    private function getVagasPorSetor($usuarioId = null)
    {
        $setores = ['GRADUACAO', 'POS_PESQUISA', 'AREA_TECNOLOGICA'];
        $vagasPorSetor = [];
        
        foreach ($setores as $setor) {
            $query = Vaga::where('setor', $setor);
            
            if ($usuarioId) {
                $query->where('usuario_id', $usuarioId);
            }
            
            $vagasPorSetor[] = [
                'setor' => $this->getNomeSetor($setor),
                'total' => $query->count(),
                'abertas' => $query->clone()->where('status', 'aberto')->count(),
                'encerradas' => $query->clone()->where('status', 'encerrado')->count(),
            ];
        }
        
        return $vagasPorSetor;
    }
    
    private function getNomeSetor($codigo)
    {
        $nomes = [
            'GRADUACAO' => 'Graduação',
            'POS_PESQUISA' => 'Pós/Pesquisa',
            'AREA_TECNOLOGICA' => 'Tecnológico',
        ];
        
        return $nomes[$codigo] ?? $codigo;
    }
}