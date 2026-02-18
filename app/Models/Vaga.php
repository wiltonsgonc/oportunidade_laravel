<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vaga extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'edital',
        'setor',
        'tipo',
        'programa_curso_area',
        'data_limite',
        'numero_de_vagas',
        'taxa_inscricao',
        'mensalidade_bolsa',
        'email_responsavel',
        'descricao',
        'arquivo_edital',
        'nome_original_edital',
        'arquivo_resultados',
        'nome_original_resultados',
        'hash_edital',
        'hash_resultados',
        'link_inscricao',
        'status',
        'anexos_count',
        'retificacoes_count',
        'criado_por',
        'atualizado_por',
    ];
    
    protected $casts = [
        'data_limite' => 'date',
        'numero_de_vagas' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    /**
     * Usuário que criou a vaga.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'criado_por');
    }

    /**
     * Anexos da vaga.
     */
    public function anexos()
    {
        return $this->hasMany(VagaAnexo::class, 'vaga_id')->orderBy('created_at', 'desc');
    }
}