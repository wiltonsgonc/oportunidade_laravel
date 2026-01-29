<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('senha');
            $table->boolean('is_admin')->default(false);
            $table->string('token_reset', 255)->nullable();
            $table->dateTime('token_expira')->nullable();
            $table->timestamps();
            
            $table->index('usuario');
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};