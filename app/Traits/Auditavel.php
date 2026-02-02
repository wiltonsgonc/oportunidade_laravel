<?php

namespace App\Traits;

use App\Models\VagaAuditoria;
use Illuminate\Support\Facades\Auth;

trait Auditavel
{
    /**
     * Boot the trait
     */
    public static function bootAuditavel()
    {
        // Registrar evento created
        static::created(function ($model) {
            $model->registrarAuditoria('criado');
        });

        // Registrar evento updated
        static::updated(function ($model) {
            $model->registrarAuditoria('atualizado');
        });

        // Registrar evento deleted (soft delete)
        static::deleted(function ($model) {
            $model->registrarAuditoria('excluido');
        });

        // Registrar evento restored (soft delete)
        static::restored(function ($model) {
            $model->registrarAuditoria('restaurado');
        });
    }

    /**
     * Registrar uma auditoria
     */
    public function registrarAuditoria($acao)
    {
        // Verificar se estamos em um contexto web (com request)
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return; // Não registrar auditoria em comandos CLI
        }

        $usuario = Auth::user();
        
        VagaAuditoria::create([
            'vaga_id' => $this->id,
            'dados_vaga' => $this->getOriginal(), // Dados antigos antes da alteração
            'usuario_id' => $usuario ? $usuario->id : null,
            'usuario_nome' => $usuario ? ($usuario->nome ?? $usuario->usuario) : 'Sistema',
            'tipo_acao' => $acao,
            'created_at' => now()
        ]);
    }

    /**
     * Registrar auditoria manualmente (para ações específicas)
     */
    public function registrarAuditoriaManual($acao, $dadosAdicionais = [])
    {
        $usuario = Auth::user();
        
        $dadosCompletos = array_merge(
            $this->getOriginal(),
            $dadosAdicionais
        );
        
        return VagaAuditoria::create([
            'vaga_id' => $this->id,
            'dados_vaga' => $dadosCompletos,
            'usuario_id' => $usuario ? $usuario->id : null,
            'usuario_nome' => $usuario ? ($usuario->nome ?? $usuario->usuario) : 'Sistema',
            'tipo_acao' => $acao
        ]);
    }
}