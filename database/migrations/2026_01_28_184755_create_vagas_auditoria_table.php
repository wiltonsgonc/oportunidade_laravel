<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vagas_auditoria', function (Blueprint $table) {
            $table->id();
            $table->integer('vaga_id');
            $table->json('dados_vaga');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('usuario_nome', 255);
            $table->enum('tipo_acao', ['EXCLUSAO_PERMANENTE', 'ARQUIVAMENTO', 'RESTAURACAO']);
            $table->timestamps();
            
            $table->index('vaga_id');
            $table->index('usuario_id');
            $table->index('tipo_acao');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vagas_auditoria');
    }
};