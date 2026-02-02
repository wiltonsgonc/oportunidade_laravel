<?php
// app/Models/VagaRetificacao.php
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
        'hash_retificacao',
        'tamanho',
        'mime_type',
        'criado_por',
        'data_publicacao'
    ];

    protected $casts = [
        'data_publicacao' => 'datetime'
    ];

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'vaga_id');
    }

    public function criador()
    {
        return $this->belongsTo(Usuario::class, 'criado_por');
    }
}