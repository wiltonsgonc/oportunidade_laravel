<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Criar usuário administrador padrão se não existir
        if (!Usuario::where('usuario', 'admin')->exists()) {
            Usuario::create([
                'nome' => 'Administrador',
                'usuario' => 'admin',
                'email' => 'admin@senai.com',
                'senha' => Hash::make('password'), // Campo 'senha' em vez de 'password'
                'is_admin' => true,
                'is_admin_principal' => true,
                'ativo' => true,
            ]);

            $this->command->info('Usuário administrador criado!');
            $this->command->info('Usuário: admin');
            $this->command->info('Email: admin@senai.com');
            $this->command->info('Senha: password');
        } else {
            $this->command->info('Usuário administrador já existe!');
        }
    }
}