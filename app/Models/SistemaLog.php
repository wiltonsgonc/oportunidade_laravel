<?php
// app/Models/SistemaLog.php
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
        'contexto',
        'usuario_id',
        'ip_address',
        'user_agent',
        'url',
        'metodo'
    ];

    protected $casts = [
        'contexto' => 'array'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scopes
    public function scopeInfo($query)
    {
        return $query->where('nivel', 'info');
    }

    public function scopeWarning($query)
    {
        return $query->where('nivel', 'warning');
    }

    public function scopeError($query)
    {
        return $query->where('nivel', 'error');
    }

    public function scopeCritical($query)
    {
        return $query->where('nivel', 'critical');
    }

    // Método helper para criar logs
    public static function criar($nivel, $mensagem, $contexto = [], $usuarioId = null)
    {
        $request = request();
        
        return self::create([
            'nivel' => $nivel,
            'mensagem' => $mensagem,
            'contexto' => $contexto,
            'usuario_id' => $usuarioId ?? auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'metodo' => $request->method()
        ]);
    }
}