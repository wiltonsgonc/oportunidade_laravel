<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vaga;
use App\Models\SistemaLog;
use App\Models\Usuario;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Registrar log de acesso
        if (Auth::check()) {
            try {
                SistemaLog::create([
                    'nivel' => 'info',
                    'mensagem' => 'Acesso ao dashboard',
                    'usuario_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'metodo' => $request->method()
                ]);
            } catch (\Exception $e) {
                // Log do erro sem interromper o fluxo
                \Log::error('Erro ao registrar log de acesso: ' . $e->getMessage());
            }
        }
        
        // Buscar estatísticas
        $totalVagas = Vaga::count();
        $vagasAbertas = Vaga::where('status', 'aberto')->count();
        $vagasEncerradas = Vaga::where('status', 'encerrado')->count();
        $ultimasVagas = Vaga::orderBy('created_at', 'desc')->take(10)->get();
        
        return view('dashboard.index', compact(
            'totalVagas', 
            'vagasAbertas', 
            'vagasEncerradas',
            'ultimasVagas'
        ));
    }
}