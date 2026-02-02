<?php
// app/Models/VagaAnexo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VagaAnexo extends Model
{
    use HasFactory;

    protected $table = 'vaga_anexos';
    
    protected $fillable = [
        'vaga_id',
        'nome_arquivo',
        'nome_original',
        'descricao',
        'hash_anexo',
        'tamanho',
        'mime_type',
        'criado_por'
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