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
            // Verificar se é soft delete ou force delete
            // Se o model usa SoftDeletes e não está sendo forçado, registra como 'excluido' (soft delete)
            if (method_exists($model, 'trashed') && !$model->isForceDeleting()) {
                $model->registrarAuditoria('excluido');
            }
        });

        // Registrar evento restored (soft delete)
        static::restored(function ($model) {
            $model->registrarAuditoria('restaurado');
        });

        // Registrar evento forceDeleted (permanente)
        static::forceDeleted(function ($model) {
            $model->registrarAuditoria('excluido_permanentemente');
        });
    }

    /**
     * Registrar uma auditoria
     */
    public function registrarAuditoria($acao)
    {
        // Verificar se estamos em um contexto web (com request) ou em testes
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return; // Não registrar auditoria em comandos CLI (exceto testes)
        }

        $usuario = Auth::user();
        
        // Obter os dados originais antes da alteração
        $dados = $this->getOriginal();
        
        // Se não houver dados originais (no caso de criação), usar os atuais
        if (empty($dados)) {
            $dados = $this->toArray();
        }
        
        VagaAuditoria::create([
            'vaga_id' => $this->id,
            'dados_vaga' => $dados,
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
        
        // Obter os dados originais antes da alteração
        $dadosOriginais = $this->getOriginal();
        
        // Se não houver dados originais (no caso de criação), usar os atuais
        if (empty($dadosOriginais)) {
            $dadosOriginais = $this->toArray();
        }
        
        $dadosCompletos = array_merge(
            $dadosOriginais,
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