<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    use HasFactory;

    protected $table = 'vagas';
    
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
        'retificacoes_count'
    ];

    protected $casts = [
        'data_limite' => 'date',
        'numero_de_vagas' => 'integer',
        'anexos_count' => 'integer',
        'retificacoes_count' => 'integer'
    ];

    public function anexos()
    {
        return $this->hasMany(VagaAnexo::class, 'vaga_id');
    }

    public function retificacoes()
    {
        return $this->hasMany(VagaRetificacao::class, 'vaga_id');
    }

    public function auditorias()
    {
        return $this->hasMany(VagaAuditoria::class, 'vaga_id');
    }

    public function scopeAbertas($query)
    {
        return $query->where('status', 'aberto');
    }

    public function scopeEncerradas($query)
    {
        return $query->where('status', 'encerrado');
    }

    public function scopeArquivadas($query)
    {
        return $query->where('status', 'arquivado');
    }
}