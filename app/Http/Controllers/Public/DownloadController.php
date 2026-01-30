<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vaga;
use App\Models\VagaAnexo;
use App\Models\VagaRetificacao;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    /**
     * Baixar edital
     */
    public function edital($id, $token)
    {
        return $this->downloadVagaArquivo($id, 'edital', $token);
    }

    /**
     * Baixar resultados
     */
    public function resultados($id, $token)
    {
        return $this->downloadVagaArquivo($id, 'resultados', $token);
    }

    /**
     * Baixar anexo
     */
    public function anexo($id, $token)
    {
        $anexo = VagaAnexo::find($id);
        
        if (!$anexo) {
            abort(404, 'Anexo não encontrado');
        }

        // Verificar token
        $expectedToken = hash_hmac('sha256', $id, 'vagas_secret_key_anexos');
        if (!hash_equals($expectedToken, $token)) {
            abort(403, 'Token inválido');
        }

        $path = storage_path('app/uploads/anexos/' . $anexo->nome_arquivo);
        
        if (!file_exists($path)) {
            abort(404, 'Arquivo não encontrado');
        }

        return Response::download($path, $anexo->nome_original);
    }

    /**
     * Baixar retificação
     */
    public function retificacao($id, $token)
    {
        $retificacao = VagaRetificacao::find($id);
        
        if (!$retificacao) {
            abort(404, 'Retificação não encontrada');
        }

        // Verificar token
        $expectedToken = hash_hmac('sha256', $id, 'vagas_secret_key_retificacoes');
        if (!hash_equals($expectedToken, $token)) {
            abort(403, 'Token inválido');
        }

        $path = storage_path('app/uploads/retificacoes/' . $retificacao->nome_arquivo);
        
        if (!file_exists($path)) {
            abort(404, 'Arquivo não encontrado');
        }

        return Response::download($path, $retificacao->nome_original);
    }

    /**
     * Função auxiliar para download de arquivos da vaga
     */
    private function downloadVagaArquivo($vagaId, $tipo, $token)
    {
        $vaga = Vaga::find($vagaId);
        
        if (!$vaga) {
            abort(404, 'Vaga não encontrada');
        }

        // Determinar configurações baseadas no tipo
        $config = [
            'edital' => [
                'campo_arquivo' => 'arquivo_edital',
                'campo_nome_original' => 'nome_original_edital',
                'secret_key' => 'vagas_secret_key_edital',
                'diretorio' => 'editais'
            ],
            'resultados' => [
                'campo_arquivo' => 'arquivo_resultados',
                'campo_nome_original' => 'nome_original_resultados',
                'secret_key' => 'vagas_secret_key_resultados',
                'diretorio' => 'resultados'
            ]
        ];

        if (!isset($config[$tipo])) {
            abort(400, 'Tipo de arquivo inválido');
        }

        $cfg = $config[$tipo];

        // Verificar token
        $expectedToken = hash_hmac('sha256', $vagaId, $cfg['secret_key']);
        if (!hash_equals($expectedToken, $token)) {
            abort(403, 'Token inválido');
        }

        // Verificar se o arquivo existe
        $nomeArquivo = $vaga->{$cfg['campo_arquivo']};
        $nomeOriginal = $vaga->{$cfg['campo_nome_original']} ?? $nomeArquivo;

        if (empty($nomeArquivo)) {
            abort(404, 'Arquivo não disponível');
        }

        $path = storage_path('app/uploads/' . $cfg['diretorio'] . '/' . $nomeArquivo);
        
        if (!file_exists($path)) {
            abort(404, 'Arquivo não encontrado no servidor');
        }

        return Response::download($path, $nomeOriginal);
    }
}