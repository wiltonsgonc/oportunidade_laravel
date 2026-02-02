<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vaga;
use App\Models\SistemaLog;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Protege todas as rotas do dashboard
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Registrar log de acesso
        if (Auth::check()) {
            SistemaLog::create([
                'nivel' => 'info',
                'mensagem' => 'Acesso ao dashboard',
                'contexto' => ['user_id' => Auth::id()],
                'usuario_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'metodo' => $request->method()
            ]);
        }
        
        // Buscar vagas (exemplo)
        $vagas = Vaga::orderBy('created_at', 'desc')->take(10)->get();
        
        return view('dashboard.index', compact('vagas'));
    }
}