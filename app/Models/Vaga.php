<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditavel;

class Vaga extends Model
{
    use SoftDeletes, Auditavel;
    
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

    /**
     * Retificações da vaga.
     */
    public function retificacoes()
    {
        return $this->hasMany(VagaRetificacao::class, 'vaga_id')->orderBy('created_at', 'desc');
    }

    /**
     * Verifica se a vaga está vencida (data_limite passou).
     */
    public function estaVencida(): bool
    {
        return $this->data_limite->isPast();
    }

    /**
     * Encerra a vaga se estiver vencida.
     */
    public function encerrarSeVencida(): bool
    {
        if ($this->estaVencida() && $this->status === 'aberto') {
            $this->update(['status' => 'encerrado']);
            return true;
        }
        return false;
    }

    /**
     * Escopo para buscar vagas abertas vencidas.
     */
    public function scopeAbertasVencidas($query)
    {
        return $query->where('status', 'aberto')
                     ->where('data_limite', '<', now()->startOfDay());
    }

    /**
     * Encerra todas as vagas abertas que estão vencidas.
     */
    public static function encerrarVagasVencidas(): int
    {
        $vagasVencidas = static::query()->abertasVencidas()->get();
        $contagem = 0;

        foreach ($vagasVencidas as $vaga) {
            if ($vaga->encerrarSeVencida()) {
                $contagem++;
            }
        }

        return $contagem;
    }
}