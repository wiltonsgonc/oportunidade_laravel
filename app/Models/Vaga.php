<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditavel;

class Vaga extends Model
{
    use HasFactory, SoftDeletes, Auditavel;
    
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
        'criado_por',
        'atualizado_por'
    ];

    protected $casts = [
        'data_limite' => 'date',
        'numero_de_vagas' => 'integer',
        'taxa_inscricao' => 'decimal:2',
        'mensalidade_bolsa' => 'decimal:2'
    ];

    // ADICIONAR ESTA LINHA:
    protected $dates = ['deleted_at'];

    // Relacionamentos
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

    public function criador()
    {
        return $this->belongsTo(Usuario::class, 'criado_por');
    }

    public function atualizador()
    {
        return $this->belongsTo(Usuario::class, 'atualizado_por');
    }

    // Accessors
    public function getAnexosCountAttribute()
    {
        if (isset($this->attributes['anexos_count'])) {
            return $this->attributes['anexos_count'];
        }
        
        if ($this->relationLoaded('anexos')) {
            return $this->anexos->count();
        }
        
        return $this->anexos()->count();
    }

    public function getRetificacoesCountAttribute()
    {
        if (isset($this->attributes['retificacoes_count'])) {
            return $this->attributes['retificacoes_count'];
        }
        
        if ($this->relationLoaded('retificacoes')) {
            return $this->retificacoes->count();
        }
        
        return $this->retificacoes()->count();
    }

    // Scopes
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

    public function scopeAtivas($query)
    {
        return $query->where('status', '!=', 'arquivado');
    }

    public function scopeExpiradas($query)
    {
        return $query->where('data_limite', '<', now());
    }

    public function scopeVencendoEm($query, $dias = 7)
    {
        return $query->where('status', 'aberto')
                    ->whereBetween('data_limite', [now(), now()->addDays($dias)]);
    }

    // Métodos de negócio
    public function isAberta()
    {
        return $this->status === 'aberto';
    }

    public function isEncerrada()
    {
        return $this->status === 'encerrado';
    }

    public function isArquivada()
    {
        return $this->status === 'arquivado';
    }

    public function isExpirada()
    {
        return $this->data_limite && $this->data_limite < now();
    }
}