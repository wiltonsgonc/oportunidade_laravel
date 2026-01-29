<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'usuario',
        'email',
        'senha',
        'is_admin',
        'token_reset',
        'token_expira'
    ];

    protected $hidden = [
        'senha',
        'token_reset'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'token_expira' => 'datetime'
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function auditorias()
    {
        return $this->hasMany(VagaAuditoria::class, 'usuario_id');
    }

    public function logs()
    {
        return $this->hasMany(SistemaLog::class, 'usuario_id');
    }
}