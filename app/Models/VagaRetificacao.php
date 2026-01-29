<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VagaRetificacao extends Model
{
    use HasFactory;

    protected $table = 'vaga_retificacoes';
    
    protected $fillable = [
        'vaga_id',
        'nome_arquivo',
        'nome_original',
        'descricao',
        'hash_retificacao'
    ];

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'vaga_id');
    }
}