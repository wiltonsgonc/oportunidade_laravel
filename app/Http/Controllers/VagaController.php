<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Vaga;
use App\Models\VagaAnexo;
use App\Models\VagaRetificacao;
use App\Models\Usuario;

class VagaController extends Controller
{
    // ============ MÉTODOS PÚBLICOS ============
    
    public function index(Request $request)
    {
        $setor = $request->query('setor');
        $status = $request->query('status', 'aberto');

        $query = Vaga::where('status', $status);

        if ($setor) {
            $setorNormalizado = trim(urldecode($setor));
            $query->where('setor', 'LIKE', '%' . $setorNormalizado . '%');
        }

        $query->orderBy('data_limite', $status === 'aberto' ? 'asc' : 'desc');

        $vagas = $query->with(['anexos', 'retificacoes'])->paginate(12);

        $setorNome = null;
        $filtroClasse = 'filtro-padrao';
        
        $setorNomes = [
            'PRO-REITORIA DE GRADUAÇÃO' => 'Graduação e Extensão',
            'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA' => 'Pós-Graduação e Pesquisa',
            'ÁREA TECNOLÓGICA SENAI CIMATEC' => 'Projetos de Inovação',
        ];

        foreach ($setorNomes as $key => $nome) {
            if ($setor && str_contains($setorNormalizado, str_replace(' ', '', $key))) {
                $setorNome = $nome;
                $filtroClasses = [
                    'PRO-REITORIA DE GRADUAÇÃO' => 'filtro-graduacao',
                    'PRO-REITORIA DE PÓS-GRADUAÇÃO E PESQUISA' => 'filtro-pos-pesquisa',
                    'ÁREA TECNOLÓGICA SENAI CIMATEC' => 'filtro-tecnologico',
                ];
                $filtroClasse = $filtroClasses[$key] ?? 'filtro-padrao';
                break;
            }
        }

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
        // Download de anexos usa o ID do anexo diretamente
        if ($tipo === 'anexo') {
            $anexo = VagaAnexo::findOrFail($id);
            $path = storage_path('app/public/' . $anexo->nome_arquivo);
            
            if (!file_exists($path)) {
                abort(404, 'Arquivo não encontrado no servidor');
            }
            
            $mimeType = mime_content_type($path);
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $anexo->nome_original . '"'
            ]);
        }

        $vaga = Vaga::findOrFail($id);

        switch ($tipo) {
            case 'edital':
                $nomeOriginal = $vaga->nome_original_edital;
                if (!$nomeOriginal) {
                    abort(404, 'Edital não encontrado');
                }
                $path = null;
                $arquivo = $vaga->arquivo_edital;
                if ($arquivo && $arquivo !== '0') {
                    $path = storage_path('app/public/' . $arquivo);
                }
                if (!$path || !file_exists($path)) {
                    abort(404, 'Arquivo físico não encontrado. O arquivo pode ter sido removido do servidor. Por favor, faça upload de um novo arquivo.');
                }
                
                $mimeType = mime_content_type($path);
                return response()->file($path, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $nomeOriginal . '"'
                ]);

            case 'resultados':
                $nomeOriginal = $vaga->nome_original_resultados;
                if (!$nomeOriginal) {
                    abort(404, 'Resultados não encontrados');
                }
                $path = null;
                $arquivo = $vaga->arquivo_resultados;
                if ($arquivo && $arquivo !== '0') {
                    $path = storage_path('app/public/' . $arquivo);
                }
                if (!$path || !file_exists($path)) {
                    abort(404, 'Arquivo físico não encontrado. O arquivo pode ter sido removido do servidor. Por favor, faça upload de um novo arquivo.');
                }
                
                $mimeType = mime_content_type($path);
                return response()->file($path, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $nomeOriginal . '"'
                ]);

            case 'retificacao':
                $retificacao = VagaRetificacao::findOrFail($id);
                $path = storage_path('app/public/' . $retificacao->nome_arquivo);
                
                if (!file_exists($path)) {
                    abort(404, 'Arquivo não encontrado no servidor');
                }
                
                $mimeType = mime_content_type($path);
                return response()->file($path, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $retificacao->nome_original . '"'
                ]);

            default:
                abort(404, 'Tipo de download inválido');
        }
    }

    public function excluirArquivo($id, $tipo)
    {
        $vaga = Vaga::findOrFail($id);
        $usuario = auth()->user();

        if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
            abort(403, 'Você não tem permissão para excluir arquivos desta vaga.');
        }

        $campoArquivo = 'arquivo_' . $tipo;
        $campoNomeOriginal = 'nome_original_' . $tipo;
        $campoHash = 'hash_' . $tipo;

        $arquivo = $vaga->$campoArquivo;
        
        if ($arquivo && $arquivo !== '0') {
            $caminho = storage_path('app/public/' . $arquivo);
            if (file_exists($caminho)) {
                unlink($caminho);
            }
        }

        $vaga->update([
            $campoArquivo => '0',
            $campoNomeOriginal => null,
            $campoHash => null,
        ]);

        return back()->with('success', 'Arquivo do ' . $tipo . ' excluído com sucesso.');
    }

    private function limparArquivosOrfaos()
    {
        $diretorio = storage_path('app/public/vagas/editais');
        if (!is_dir($diretorio)) {
            return;
        }

        $arquivosNoDiretorio = glob($diretorio . '/*');
        
        $vagas = Vaga::whereNotNull('arquivo_edital')
            ->where('arquivo_edital', '!=', '0')
            ->orWhereNotNull('arquivo_resultados')
            ->where('arquivo_resultados', '!=', '0')
            ->get();

        $arquivosUsados = [];
        foreach ($vagas as $vaga) {
            if ($vaga->arquivo_edital && $vaga->arquivo_edital !== '0') {
                $arquivosUsados[] = $vaga->arquivo_edital;
            }
            if ($vaga->arquivo_resultados && $vaga->arquivo_resultados !== '0') {
                $arquivosUsados[] = $vaga->arquivo_resultados;
            }
        }

        foreach ($arquivosNoDiretorio as $arquivo) {
            if (is_file($arquivo)) {
                $nomeArquivo = basename($arquivo);
                if (!in_array('vagas/editais/' . $nomeArquivo, $arquivosUsados)) {
                    unlink($arquivo);
                }
            }
        }
    }

    // ============ MÉTODOS DE CRUD (AUTENTICADOS) ============

    public function create()
    {
        return view('vagas.criar');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'edital' => 'required|string|max:500',
            'setor' => 'required|string',
            'tipo' => 'required|string',
            'programa_curso_area' => 'required|string|max:500',
            'email_responsavel' => 'required|email|max:255',
            'data_limite' => 'required|date',
            'numero_de_vagas' => 'required|integer|min:1',
            'taxa_inscricao' => 'nullable|string|max:100',
            'mensalidade_bolsa' => 'nullable|string|max:100',
            'link_inscricao' => 'required|string|max:512',
            'descricao' => 'required|string',
            'arquivo_edital' => 'required|file|mimes:pdf,doc,docx,odt|max:10240',
        ]);

        // Mapear campos do formulário para o banco
        $dados = [
            'edital' => $validated['edital'],
            'setor' => $validated['setor'],
            'tipo' => $validated['tipo'],
            'programa_curso_area' => $validated['programa_curso_area'],
            'email_responsavel' => $validated['email_responsavel'],
            'data_limite' => $validated['data_limite'],
            'numero_de_vagas' => $validated['numero_de_vagas'],
            'taxa_inscricao' => $validated['taxa_inscricao'] ?? 'Não se aplica',
            'mensalidade_bolsa' => $validated['mensalidade_bolsa'] ?? 'Não se aplica',
            'link_inscricao' => $validated['link_inscricao'],
            'descricao' => $validated['descricao'],
            'status' => 'aberto',
        ];

        // Processar upload do arquivo do edital
        if ($request->hasFile('arquivo_edital')) {
            $arquivo = $request->file('arquivo_edital');
            $nomeOriginal = $arquivo->getClientOriginalName();
            $hashEdital = hash_file('sha256', $arquivo->getRealPath());
            
            $diretorio = storage_path('app/public/vagas/editais');
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }
            
            $nomeUnico = uniqid() . '_' . $nomeOriginal;
            $caminhoCompleto = $diretorio . '/' . $nomeUnico;
            $arquivo->move($diretorio, $nomeUnico);
            
            $dados['arquivo_edital'] = 'vagas/editais/' . $nomeUnico;
            $dados['nome_original_edital'] = $nomeOriginal;
            $dados['hash_edital'] = $hashEdital;
        }

        // Adicionar usuário autenticado como criador
        $dados['criado_por'] = auth()->id();

        // Criar vaga
        Vaga::create($dados);

        return redirect()->route('dashboard')
            ->with('success', 'Vaga cadastrada com sucesso!');
    }

    public function anexos($id)
    {
        $vaga = Vaga::findOrFail($id);
        
        $usuario = auth()->user();
        if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
            abort(403, 'Você não tem permissão para gerenciar anexos desta vaga.');
        }

        $vaga->load('anexos');

        return view('vagas.anexos', compact('vaga'));
    }

    public function edit($id)
    {
        $vaga = Vaga::findOrFail($id);
        
        // Verificar se o usuário tem permissão para editar
        $usuario = auth()->user();
        if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
            abort(403, 'Você não tem permissão para editar esta vaga.');
        }

        $vaga->load('anexos');

        return view('vagas.edit', compact('vaga'));
    }

    public function update(Request $request, $id)
    {
        $vaga = Vaga::findOrFail($id);
        
        // Verificar se o usuário tem permissão para editar
        $usuario = auth()->user();
        if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
            abort(403, 'Você não tem permissão para editar esta vaga.');
        }

        $validated = $request->validate([
            'edital' => 'required|string|max:500',
            'setor' => 'required|string',
            'tipo' => 'required|string',
            'programa_curso_area' => 'required|string|max:500',
            'email_responsavel' => 'required|email|max:255',
            'data_limite' => 'required|date',
            'numero_de_vagas' => 'required|integer|min:1',
            'taxa_inscricao' => 'nullable|string|max:100',
            'mensalidade_bolsa' => 'nullable|string|max:100',
            'link_inscricao' => 'required|string|max:512',
            'descricao' => 'required|string',
            'arquivo_edital' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240',
            'arquivo_resultados' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240',
            'status' => 'required|string|in:aberto,encerrado',
        ]);

        // Processar upload de arquivos
        if ($request->hasFile('arquivo_edital')) {
            // Remover arquivo antigo se existir
            if ($vaga->arquivo_edital && $vaga->arquivo_edital !== '0') {
                $caminhoAntigo = storage_path('app/public/' . $vaga->arquivo_edital);
                if (file_exists($caminhoAntigo)) {
                    unlink($caminhoAntigo);
                }
            }
            
            $diretorio = storage_path('app/public/vagas/editais');
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }
            
            $nomeOriginal = $request->file('arquivo_edital')->getClientOriginalName();
            $nomeUnico = uniqid() . '_' . $nomeOriginal;
            $caminhoCompleto = $diretorio . '/' . $nomeUnico;
            $request->file('arquivo_edital')->move($diretorio, $nomeUnico);
            
            $validated['arquivo_edital'] = 'vagas/editais/' . $nomeUnico;
            $validated['nome_original_edital'] = $nomeOriginal;
            $validated['hash_edital'] = hash_file('sha256', $caminhoCompleto);
        }

        if ($request->hasFile('arquivo_resultados')) {
            // Remover arquivo antigo se existir
            if ($vaga->arquivo_resultados && $vaga->arquivo_resultados !== '0') {
                $caminhoAntigo = storage_path('app/public/' . $vaga->arquivo_resultados);
                if (file_exists($caminhoAntigo)) {
                    unlink($caminhoAntigo);
                }
            }
            
            $diretorio = storage_path('app/public/vagas/resultados');
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }
            
            $nomeOriginal = $request->file('arquivo_resultados')->getClientOriginalName();
            $nomeUnico = uniqid() . '_' . $nomeOriginal;
            $caminhoCompleto = $diretorio . '/' . $nomeUnico;
            $request->file('arquivo_resultados')->move($diretorio, $nomeUnico);
            
            $validated['arquivo_resultados'] = 'vagas/resultados/' . $nomeUnico;
            $validated['nome_original_resultados'] = $nomeOriginal;
            $validated['hash_resultados'] = hash_file('sha256', $caminhoCompleto);
        }

        $vaga->update($validated);

        $this->limparArquivosOrfaos();

        return redirect()->route('dashboard')
            ->with('success', 'Vaga atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $vaga = Vaga::findOrFail($id);
        
        // Verificar se o usuário tem permissão para excluir
        $usuario = auth()->user();
        if ($vaga->criado_por !== $usuario->id && !$usuario->is_admin) {
            abort(403, 'Você não tem permissão para excluir esta vaga.');
        }

        $vaga->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Vaga excluída com sucesso!');
    }

    // ============ MÉTODOS PARA AÇÕES DO DASHBOARD ============

    public function paraEditar()
    {
        $vagas = Vaga::where('criado_por', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('vagas.editar', compact('vagas'));
    }

    public function paraExcluir()
    {
        $vagas = Vaga::where('criado_por', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('vagas.excluir', compact('vagas'));
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

        $this->limparArquivosOrfaos();

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