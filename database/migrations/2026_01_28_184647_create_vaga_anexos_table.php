<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vaga_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaga_id')->constrained('vagas')->onDelete('cascade');
            $table->string('nome_arquivo');
            $table->string('nome_original');
            $table->text('descricao')->nullable();
            $table->string('hash_anexo');
            $table->integer('tamanho')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->foreignId('criado_por')->nullable()->constrained('usuarios');
            $table->timestamps();
            
            $table->index('vaga_id');
            $table->index('hash_anexo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vaga_anexos');
    }
};