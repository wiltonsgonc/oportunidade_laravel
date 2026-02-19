<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaga;
use App\Models\VagaRetificacao;

class RetificacaoController extends Controller
{
    public function index($id)
    {
        $vaga = Vaga::findOrFail($id);
        
        $usuario = auth()->user();
        if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
            abort(403, 'Você não tem permissão para gerenciar retificações desta vaga.');
        }

        $vaga->load('retificacoes');

        return view('vagas.retificacoes', compact('vaga'));
    }

    public function upload(Request $request, $id)
    {
        try {
            $vaga = Vaga::findOrFail($id);
            $usuario = auth()->user();

            if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
                return response()->json(['error' => 'Você não tem permissão para adicionar retificações.'], 403);
            }

            $countRetificacoes = VagaRetificacao::where('vaga_id', $id)->count();
            if ($countRetificacoes >= 10) {
                return response()->json(['error' => 'Limite máximo de 10 retificações atingido.'], 422);
            }

            $request->validate([
                'retificacao' => 'required|file|mimes:pdf,doc,docx,odt|max:10240',
                'descricao' => 'nullable|string|max:500'
            ]);

            $arquivo = $request->file('retificacao');
            $nomeOriginal = $arquivo->getClientOriginalName();
            $hash = hash_file('sha256', $arquivo->getRealPath());
            
            $diretorio = storage_path('app/public/vagas/retificacoes');
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            $nomeUnico = uniqid() . '_' . $nomeOriginal;
            $arquivo->move($diretorio, $nomeUnico);

            $retificacao = VagaRetificacao::create([
                'vaga_id' => $id,
                'nome_arquivo' => 'vagas/retificacoes/' . $nomeUnico,
                'nome_original' => $nomeOriginal,
                'descricao' => $request->input('descricao', ''),
                'hash_retificacao' => $hash
            ]);

            $vaga->increment('retificacoes_count');

            return response()->json([
                'success' => true,
                'retificacao' => $retificacao
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao salvar retificação: ' . $e->getMessage()], 500);
        }
    }

    public function excluir($id, $retificacaoId)
    {
        try {
            $vaga = Vaga::findOrFail($id);
            $usuario = auth()->user();

            if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
                return response()->json(['error' => 'Você não tem permissão para excluir retificações.'], 403);
            }

            $retificacao = VagaRetificacao::where('id', $retificacaoId)->where('vaga_id', $id)->firstOrFail();

            $caminho = storage_path('app/public/' . $retificacao->nome_arquivo);
            if (file_exists($caminho)) {
                unlink($caminho);
            }

            $retificacao->delete();
            $vaga->decrement('retificacoes_count');

            $this->limparRetificacoesOrfaos();

            return response()->json(['success' => true, 'message' => 'Retificação excluída com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir retificação: ' . $e->getMessage()], 500);
        }
    }

    public function limparRetificacoesOrfaos()
    {
        $diretorio = storage_path('app/public/vagas/retificacoes');
        if (!is_dir($diretorio)) {
            return;
        }

        $arquivosNoDiretorio = glob($diretorio . '/*');
        
        $retificacoes = VagaRetificacao::all();
        $arquivosUsados = $retificacoes->pluck('nome_arquivo')->toArray();

        foreach ($arquivosNoDiretorio as $arquivo) {
            if (is_file($arquivo)) {
                $nomeArquivo = 'vagas/retificacoes/' . basename($arquivo);
                if (!in_array($nomeArquivo, $arquivosUsados)) {
                    unlink($arquivo);
                }
            }
        }
    }
}
