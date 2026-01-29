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
            $table->string('nome_arquivo', 255);
            $table->string('nome_original', 255);
            $table->string('descricao', 500)->nullable();
            $table->string('hash_anexo', 64)->unique();
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