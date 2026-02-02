<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaga;

class VagaController extends Controller
{
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
    
    // Método para restaurar uma vaga excluída (se necessário)
    public function restore($id)
    {
        $vaga = Vaga::withTrashed()->findOrFail($id);
        $vaga->restore();
        
        return redirect()->back()->with('success', 'Vaga restaurada com sucesso!');
    }
    
    // Método para excluir permanentemente (force delete)
    public function forceDelete($id)
    {
        $vaga = Vaga::withTrashed()->findOrFail($id);
        $vaga->forceDelete();
        
        return redirect()->back()->with('success', 'Vaga excluída permanentemente!');
    }
    
    // Métodos estáticos para as views (se necessário)
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