<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sistema_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('nivel', ['INFO', 'WARNING', 'ERROR', 'SECURITY']);
            $table->text('mensagem');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('nivel');
            $table->index('usuario_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sistema_logs');
    }
};