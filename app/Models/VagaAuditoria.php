<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VagaAuditoria extends Model
{
    use HasFactory;

    protected $table = 'vagas_auditoria';
    
    protected $fillable = [
        'vaga_id',
        'dados_vaga',
        'usuario_id',
        'usuario_nome',
        'tipo_acao'
    ];

    protected $casts = [
        'dados_vaga' => 'array'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'vaga_id');
    }
}