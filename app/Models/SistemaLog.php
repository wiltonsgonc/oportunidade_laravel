<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemaLog extends Model
{
    use HasFactory;

    protected $table = 'sistema_logs';
    
    protected $fillable = [
        'nivel',
        'mensagem',
        'usuario_id',
        'ip_address',
        'user_agent'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}