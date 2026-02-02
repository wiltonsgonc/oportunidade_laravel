<?php
// database/migrations/2026_01_28_184521_create_usuarios_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('usuario', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('senha');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_admin_principal')->default(false);
            $table->boolean('ativo')->default(true);
            $table->string('token_reset', 255)->nullable();
            $table->timestamp('token_expira')->nullable();
            $table->timestamp('ultimo_login')->nullable();
            $table->string('ip_ultimo_login', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('usuario');
            $table->index('email');
            $table->index('is_admin');
            $table->index('ativo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};