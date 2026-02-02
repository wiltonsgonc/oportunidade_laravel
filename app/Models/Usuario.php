<?php
// app/Models/Usuario.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nome',
        'usuario',
        'email',
        'senha',
        'is_admin',
        'is_admin_principal',
        'ativo',
        'token_reset',
        'token_expira',
        'ultimo_login',
        'ip_ultimo_login'
    ];

    protected $hidden = [
        'senha',
        'token_reset',
        'remember_token'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_admin_principal' => 'boolean',
        'ativo' => 'boolean',
        'token_expira' => 'datetime',
        'ultimo_login' => 'datetime'
    ];

    // Método para compatibilidade com Laravel Auth
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Mutator para hash da senha
    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = Hash::make($value);
    }

    // Relacionamentos
    public function auditorias()
    {
        return $this->hasMany(VagaAuditoria::class, 'usuario_id');
    }

    public function logs()
    {
        return $this->hasMany(SistemaLog::class, 'usuario_id');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopePrincipais($query)
    {
        return $query->where('is_admin_principal', true);
    }
}