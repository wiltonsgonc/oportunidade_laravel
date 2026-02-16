<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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

    // Método para buscar por credenciais (usuario em vez de email)
    public function findForPassport($username)
    {
        return $this->where('usuario', $username)
                    ->orWhere('email', $username)
                    ->first();
    }

    // Mutator para hash da senha
    public function setSenhaAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['senha'] = Hash::make($value);
        }
    }

    // ============ RELACIONAMENTOS ============
    
    // Vagas criadas pelo usuário
    public function vagasCriadas(): HasMany
    {
        return $this->hasMany(Vaga::class, 'usuario_id');
    }
    
    // Auditorias realizadas pelo usuário
    public function auditorias(): HasMany
    {
        return $this->hasMany(VagaAuditoria::class, 'usuario_id');
    }

    // Logs do sistema gerados pelo usuário
    public function logs(): HasMany
    {
        return $this->hasMany(SistemaLog::class, 'usuario_id');
    }

    // ============ SCOPES ============
    
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
    
    // ============ MÉTODOS UTILITÁRIOS ============
    
    public function getNomeCompletoAttribute()
    {
        return $this->nome;
    }
    
    public function getIniciaisAttribute()
    {
        $nomes = explode(' ', $this->nome);
        $iniciais = '';
        
        foreach ($nomes as $nome) {
            if (!empty($nome)) {
                $iniciais .= strtoupper(substr($nome, 0, 1));
            }
        }
        
        return substr($iniciais, 0, 2);
    }
    
    public function getStatusFormatadoAttribute()
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }
    
    public function getTipoUsuarioAttribute()
    {
        if ($this->is_admin_principal) {
            return 'Administrador Principal';
        } elseif ($this->is_admin) {
            return 'Administrador';
        } else {
            return 'Usuário';
        }
    }
}