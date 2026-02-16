<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Vaga;
use App\Models\Usuario;

class VagaController extends Controller
{
    // ============ MÉTODOS PÚBLICOS ============
    
    public function index(Request $request)
    {
        $setor = $request->query('setor');
        $status = $request->query('status', 'aberto');

        // Consulta COM soft deletes - apenas registros não excluídos
        $query = Vaga::where('status', $status);

        // Filtrar por setor se especificado
        if ($setor) {
            $query->where('setor', $setor);
        }

        // Ordenar
        $query->orderBy('data_limite', $status === 'aberto' ? 'asc' : 'desc');

        $vagas = $query->paginate(12);

        // Nome do setor para exibição
        $setorNomes = [
            'GRADUACAO' => 'Graduação e Extensão',
            'POS_PESQUISA' => 'Pós-Graduação e Pesquisa',
            'AREA_TECNOLOGICA' => 'Projetos de Inovação',
        ];

        $setorNome = $setorNomes[$setor] ?? null;

        // Classe de filtro para background
        $filtroClasses = [
            'GRADUACAO' => 'filtro-graduacao',
            'POS_PESQUISA' => 'filtro-pos-pesquisa',
            'AREA_TECNOLOGICA' => 'filtro-tecnologico',
        ];

        $filtroClasse = $filtroClasses[$setor] ?? 'filtro-padrao';

        return view('public.vagas.home', compact('vagas', 'setor', 'setorNome', 'status', 'filtroClasse'));
    }

    public function bySetor($setor)
    {
        return $this->index(request()->merge(['setor' => $setor]));
    }

    public function show($id)
    {
        $vaga = Vaga::findOrFail($id);
        return view('vagas.show', compact('vaga'));
    }

    // ============ MÉTODOS DE DOWNLOAD ============

    public function download($tipo, $id)
    {
        $vaga = Vaga::findOrFail($id);

        switch ($tipo) {
            case 'edital':
                if (!$vaga->arquivo_edital) {
                    abort(404, 'Edital não encontrado');
                }
                $path = storage_path('app/' . $vaga->arquivo_edital);
                $nomeArquivo = 'edital_' . Str::slug($vaga->edital) . '.pdf';
                break;

            case 'resultados':
                if (!$vaga->arquivo_resultados) {
                    abort(404, 'Resultados não encontrados');
                }
                $path = storage_path('app/' . $vaga->arquivo_resultados);
                $nomeArquivo = 'resultados_' . Str::slug($vaga->edital) . '.pdf';
                break;

            default:
                abort(404, 'Tipo de download inválido');
        }

        if (!file_exists($path)) {
            abort(404, 'Arquivo não encontrado');
        }

        return response()->download($path, $nomeArquivo);
    }

    // ============ MÉTODOS DE CRUD (AUTENTICADOS) ============

    public function create()
    {
        return view('vagas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'setor' => 'required|string|in:GRADUACAO,POS_PESQUISA,AREA_TECNOLOGICA',
            'status' => 'required|string|in:aberto,encerrado',
            'edital' => 'nullable|string|max:255',
            'data_limite' => 'nullable|date',
            'remuneracao' => 'nullable|string|max:100',
            'vagas_disponiveis' => 'nullable|integer|min:0',
            'requisitos' => 'nullable|string',
            'contato' => 'nullable|string|max:255',
            'arquivo_edital' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'arquivo_resultados' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Processar upload de arquivos
        if ($request->hasFile('arquivo_edital')) {
            $validated['arquivo_edital'] = $request->file('arquivo_edital')->store('vagas/editais', 'public');
        }

        if ($request->hasFile('arquivo_resultados')) {
            $validated['arquivo_resultados'] = $request->file('arquivo_resultados')->store('vagas/resultados', 'public');
        }

        // Adicionar usuário autenticado como criador
        $validated['usuario_id'] = auth()->id(); // Alterado para 'usuario_id'

        // Criar vaga
        Vaga::create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Vaga cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $vaga = Vaga::findOrFail($id);
        
        // Verificar se o usuário tem permissão para editar
        $usuario = auth()->user();
        if ($vaga->usuario_id !== $usuario->id && !$usuario->is_admin) { // Alterado para usar is_admin do Usuario
            abort(403, 'Você não tem permissão para editar esta vaga.');
        }

        return view('vagas.edit', compact('vaga'));
    }

    public function update(Request $request, $id)
    {
        $vaga = Vaga::findOrFail($id);
        
        // Verificar se o usuário tem permissão para editar
        $usuario = auth()->user();
        if ($vaga->usuario_id !== $usuario->id && !$usuario->is_admin) { // Alterado para usar is_admin do Usuario
            abort(403, 'Você não tem permissão para editar esta vaga.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'setor' => 'required|string|in:GRADUACAO,POS_PESQUISA,AREA_TECNOLOGICA',
            'status' => 'required|string|in:aberto,encerrado',
            'edital' => 'nullable|string|max:255',
            'data_limite' => 'nullable|date',
            'remuneracao' => 'nullable|string|max:100',
            'vagas_disponiveis' => 'nullable|integer|min:0',
            'requisitos' => 'nullable|string',
            'contato' => 'nullable|string|max:255',
            'arquivo_edital' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'arquivo_resultados' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Processar upload de arquivos
        if ($request->hasFile('arquivo_edital')) {
            // Remover arquivo antigo se existir
            if ($vaga->arquivo_edital) {
                Storage::disk('public')->delete($vaga->arquivo_edital);
            }
            $validated['arquivo_edital'] = $request->file('arquivo_edital')->store('vagas/editais', 'public');
        }

        if ($request->hasFile('arquivo_resultados')) {
            // Remover arquivo antigo se existir
            if ($vaga->arquivo_resultados) {
                Storage::disk('public')->delete($vaga->arquivo_resultados);
            }
            $validated['arquivo_resultados'] = $request->file('arquivo_resultados')->store('vagas/resultados', 'public');
        }

        $vaga->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Vaga atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $vaga = Vaga::findOrFail($id);
        
        // Verificar se o usuário tem permissão para excluir
        $usuario = auth()->user();
        if ($vaga->usuario_id !== $usuario->id && !$usuario->is_admin) { // Alterado para usar is_admin do Usuario
            abort(403, 'Você não tem permissão para excluir esta vaga.');
        }

        $vaga->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Vaga excluída com sucesso!');
    }

    // ============ MÉTODOS PARA AÇÕES DO DASHBOARD ============

    public function paraEditar()
    {
        $vagas = Vaga::where('usuario_id', auth()->id()) // Alterado para 'usuario_id'
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('vagas.para-editar', compact('vagas'));
    }

    public function paraExcluir()
    {
        $vagas = Vaga::where('usuario_id', auth()->id()) // Alterado para 'usuario_id'
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('vagas.para-excluir', compact('vagas'));
    }

    // ============ MÉTODOS ADMINISTRATIVOS ============

    public function adminIndex()
    {
        $vagas = Vaga::with('usuario') // Alterado para 'usuario'
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.vagas.index', compact('vagas'));
    }

    public function trash()
    {
        $vagas = Vaga::onlyTrashed()
                    ->with('usuario') // Alterado para 'usuario'
                    ->orderBy('deleted_at', 'desc')
                    ->paginate(15);

        return view('admin.vagas.trash', compact('vagas'));
    }

    public function restore($id)
    {
        $vaga = Vaga::withTrashed()->findOrFail($id);
        $vaga->restore();

        return redirect()->back()->with('success', 'Vaga restaurada com sucesso!');
    }

    public function forceDelete($id)
    {
        $vaga = Vaga::withTrashed()->findOrFail($id);
        
        // Remover arquivos associados
        if ($vaga->arquivo_edital) {
            Storage::disk('public')->delete($vaga->arquivo_edital);
        }
        if ($vaga->arquivo_resultados) {
            Storage::disk('public')->delete($vaga->arquivo_resultados);
        }

        $vaga->forceDelete();

        return redirect()->back()->with('success', 'Vaga excluída permanentemente!');
    }

    // ============ MÉTODOS UTILITÁRIOS ============

    public static function formatarDataSegura($data)
    {
        if (!$data) return 'N/A';
        try {
            return \Carbon\Carbon::parse($data)->format('d/m/Y');
        } catch (\Exception $e) {
            return 'Data inválida';
        }
    }

    public static function formatarMoedaParaExibicao($valor)
    {
        if (empty($valor)) return 'N/A';
        $valor = str_replace(['R$', '$', ' '], '', $valor);
        if (is_numeric($valor)) {
            return 'R$ ' . number_format($valor, 2, ',', '.');
        }
        return $valor;
    }

    public static function gerarTokenDownload($id, $tipo)
    {
        return hash_hmac('sha256', $id, 'vagas_secret_key_' . $tipo);
    }

    public static function extrairNomeOriginal($nomeArquivo)
    {
        $nome = pathinfo($nomeArquivo, PATHINFO_FILENAME);
        return str_replace('_', ' ', $nome);
    }
}