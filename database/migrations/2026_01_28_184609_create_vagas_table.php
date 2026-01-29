<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vagas', function (Blueprint $table) {
            $table->id();
            $table->string('edital', 500);
            $table->string('setor', 100);
            $table->string('tipo', 100);
            $table->string('programa_curso_area', 500);
            $table->date('data_limite');
            $table->integer('numero_de_vagas');
            $table->string('taxa_inscricao', 100)->default('Não se aplica');
            $table->string('mensalidade_bolsa', 100)->default('Não se aplica');
            $table->string('email_responsavel', 255);
            $table->text('descricao')->nullable();
            $table->string('arquivo_edital', 255)->nullable();
            $table->string('nome_original_edital', 255)->nullable();
            $table->string('arquivo_resultados', 255)->nullable();
            $table->string('nome_original_resultados', 255)->nullable();
            $table->string('hash_edital', 64)->nullable();
            $table->string('hash_resultados', 64)->nullable();
            $table->string('link_inscricao', 512)->nullable();
            $table->enum('status', ['aberto', 'encerrado', 'arquivado'])->default('aberto');
            $table->integer('anexos_count')->default(0);
            $table->integer('retificacoes_count')->default(0);
            $table->timestamps();
            
            $table->index('hash_edital');
            $table->index('hash_resultados');
            $table->index('status');
            $table->index('setor');
            $table->index('data_limite');
            $table->index('tipo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vagas');
    }
};