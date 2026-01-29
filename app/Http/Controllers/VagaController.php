<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VagaController extends Controller
{
    /**
     * Exibir página home de vagas (substitui home.php?setor=...)
     */
    public function home(Request $request)
    {
        $setor = $request->query('setor', 'GRADUACAO');
        
        // Converte o setor para nome amigável
        $setores = [
            'GRADUACAO' => 'Graduação e Extensão',
            'POS_PESQUISA' => 'Pós-Graduação e Pesquisa',
            'AREA_TECNOLOGICA' => 'Projetos de Inovação'
        ];
        
        $setorNome = $setores[$setor] ?? 'Graduação e Extensão';
        
        return view('vagas.home', compact('setor', 'setorNome'));
    }
}