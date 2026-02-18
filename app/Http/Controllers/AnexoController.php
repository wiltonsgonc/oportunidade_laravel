<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaga;
use App\Models\VagaAnexo;

class AnexoController extends Controller
{
    public function upload(Request $request, $id)
    {
        try {
            $vaga = Vaga::findOrFail($id);
            $usuario = auth()->user();

            if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
                return response()->json(['error' => 'Você não tem permissão para adicionar anexos.'], 403);
            }

            $countAnexos = VagaAnexo::where('vaga_id', $id)->count();
            if ($countAnexos >= 10) {
                return response()->json(['error' => 'Limite máximo de 10 anexos atingido.'], 422);
            }

            $request->validate([
                'anexo' => 'required|file|mimes:pdf,doc,docx,odt|max:10240',
                'descricao' => 'nullable|string|max:500'
            ]);

            $arquivo = $request->file('anexo');
            $nomeOriginal = $arquivo->getClientOriginalName();
            $tamanho = $arquivo->getSize();
            $mimeType = $arquivo->getMimeType();
            $hash = hash_file('sha256', $arquivo->getRealPath());
            
            $diretorio = storage_path('app/public/vagas/anexos');
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            $nomeUnico = uniqid() . '_' . $nomeOriginal;
            $arquivo->move($diretorio, $nomeUnico);

            $anexo = VagaAnexo::create([
                'vaga_id' => $id,
                'nome_arquivo' => 'vagas/anexos/' . $nomeUnico,
                'nome_original' => $nomeOriginal,
                'descricao' => $request->input('descricao', ''),
                'hash_anexo' => $hash,
                'tamanho' => $tamanho,
                'mime_type' => $mimeType,
                'criado_por' => $usuario->id
            ]);

            $vaga->increment('anexos_count');

            return response()->json([
                'success' => true,
                'anexo' => $anexo
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao salvar anexo: ' . $e->getMessage()], 500);
        }
    }

    public function excluir($id, $anexoId)
    {
        try {
            $vaga = Vaga::findOrFail($id);
            $usuario = auth()->user();

            if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
                return response()->json(['error' => 'Você não tem permissão para excluir anexos.'], 403);
            }

            $anexo = VagaAnexo::where('id', $anexoId)->where('vaga_id', $id)->firstOrFail();

            $caminho = storage_path('app/public/' . $anexo->nome_arquivo);
            if (file_exists($caminho)) {
                unlink($caminho);
            }

            $anexo->delete();
            $vaga->decrement('anexos_count');

            $this->limparAnexosOrfaos();

            return response()->json(['success' => true, 'message' => 'Anexo excluído com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir anexo: ' . $e->getMessage()], 500);
        }
    }

    public function limparAnexosOrfaos()
    {
        $diretorio = storage_path('app/public/vagas/anexos');
        if (!is_dir($diretorio)) {
            return;
        }

        $arquivosNoDiretorio = glob($diretorio . '/*');
        
        $anexos = VagaAnexo::all();
        $arquivosUsados = $anexos->pluck('nome_arquivo')->toArray();

        foreach ($arquivosNoDiretorio as $arquivo) {
            if (is_file($arquivo)) {
                $nomeArquivo = 'vagas/anexos/' . basename($arquivo);
                if (!in_array($nomeArquivo, $arquivosUsados)) {
                    unlink($arquivo);
                }
            }
        }
    }
}
