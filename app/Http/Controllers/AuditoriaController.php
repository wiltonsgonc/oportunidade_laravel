<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VagaAuditoria;
use App\Models\Vaga;
use Illuminate\Support\Facades\Auth;

class AuditoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->is_admin_principal) {
                return redirect()->route('dashboard')
                    ->with('error', 'Acesso restrito ao administrador principal.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $aba = $request->query('tab', 'auditoria');
        
        $auditorias = VagaAuditoria::with('usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $vagasArquivadas = Vaga::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('admin.auditoria.index', compact('auditorias', 'vagasArquivadas', 'aba'));
    }

    public function detalhes($id)
    {
        $auditoria = VagaAuditoria::with('usuario')->findOrFail($id);
        
        return response()->json([
            'dados' => $auditoria->dados_vaga,
            'usuario' => $auditoria->usuario_nome,
            'tipo' => $auditoria->tipo_acao,
            'data' => $auditoria->created_at->format('d/m/Y H:i:s')
        ]);
    }

    public function restaurar(Request $request)
    {
        $id = $request->input('id');
        
        $vaga = Vaga::withTrashed()->findOrFail($id);
        
        if (!$vaga->trashed()) {
            return redirect()->route('admin.auditoria.index', ['tab' => 'arquivadas'])
                ->with('error', 'A vaga não está arquivada.');
        }
        
        $vaga->restore();
        
        return redirect()->route('admin.auditoria.index', ['tab' => 'arquivadas'])
            ->with('success', 'Vaga restaurada com sucesso!');
    }

    public function excluirPermanente(Request $request)
    {
        $id = $request->input('id');
        
        $vaga = Vaga::withTrashed()->findOrFail($id);
        
        if (!$vaga->trashed()) {
            return redirect()->route('admin.auditoria.index', ['tab' => 'arquivadas'])
                ->with('error', 'A vaga não está arquivada.');
        }
        
        $vaga->forceDelete();
        
        return redirect()->route('admin.auditoria.index', ['tab' => 'arquivadas'])
            ->with('success', 'Vaga excluída permanentemente!');
    }
}
