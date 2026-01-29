<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vaga_retificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaga_id')->constrained('vagas')->onDelete('cascade');
            $table->string('nome_arquivo', 255);
            $table->string('nome_original', 255);
            $table->text('descricao')->nullable();
            $table->string('hash_retificacao', 64)->unique();
            $table->timestamps();
            
            $table->index('vaga_id');
            $table->index('hash_retificacao');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vaga_retificacoes');
    }
};