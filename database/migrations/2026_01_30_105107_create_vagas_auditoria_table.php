<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vagas_auditoria', function (Blueprint $table) {
            $table->id();
            
            // Chave estrangeira para a vaga
            $table->foreignId('vaga_id')->constrained('vagas')->onDelete('cascade');
            
            // Dados da vaga no momento da auditoria (formato JSON)
            $table->json('dados_vaga')->nullable();
            
            // Usuário que realizou a ação
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('usuario_nome', 255);
            
            // Tipo de ação (criado, atualizado, excluido, restaurado, excluido_permanentemente)
            $table->enum('tipo_acao', [
                'criado', 
                'atualizado', 
                'excluido', 
                'restaurado', 
                'excluido_permanentemente'
            ]);
            
            // Dados adicionais (para auditorias manuais)
            $table->json('dados_adicionais')->nullable();
            
            $table->timestamps();
            
            // Índices para melhor performance nas consultas
            $table->index('vaga_id');
            $table->index('tipo_acao');
            $table->index('usuario_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vagas_auditoria');
    }
};