<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vaga extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'titulo',
        'descricao',
        'setor',
        'status',
        'edital',
        'data_limite',
        'remuneracao',
        'vagas_disponiveis',
        'requisitos',
        'contato',
        'arquivo_edital',
        'arquivo_resultados',
    ];
    
    protected $casts = [
        'data_limite' => 'date',
        'vagas_disponiveis' => 'integer',
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
}