<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('=== CRIANDO USUÁRIO ADMINISTRADOR ===');
        
        // Primeiro, deletar qualquer usuário existente para evitar duplicação
        DB::table('usuarios')->where('usuario', 'admin')->orWhere('email', 'admin@senai.com')->delete();
        
        // Hash da senha 'password'
        $senhaHash = Hash::make('password');
        
        $this->command->info("Hash gerado para 'password': " . $senhaHash);
        
        // Inserir o usuário admin diretamente via SQL para garantir
        $now = Carbon::now();
        
        $id = DB::table('usuarios')->insertGetId([
            'nome' => 'Administrador',
            'usuario' => 'admin',
            'email' => 'admin@senai.com',
            'senha' => $senhaHash,
            'is_admin' => 1,
            'is_admin_principal' => 1,
            'ativo' => 1,
            'token_reset' => null,
            'token_expira' => null,
            'ultimo_login' => null,
            'ip_ultimo_login' => null,
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);
        
        $this->command->info('✓ Usuário admin criado com ID: ' . $id);
        $this->command->info('Usuário: admin');
        $this->command->info('E-mail: admin@senai.com');
        $this->command->info('Senha: password');
        
        // Verificar se foi criado corretamente
        $usuario = DB::table('usuarios')->find($id);
        if ($usuario) {
            $this->command->info('✓ Verificação: Usuário encontrado no banco');
            
            // Verificar se a senha está correta
            if (Hash::check('password', $usuario->senha)) {
                $this->command->info('✓ Verificação: Senha "password" está correta');
            } else {
                $this->command->error('✗ ERRO: Senha não corresponde ao hash');
            }
        } else {
            $this->command->error('✗ ERRO: Usuário não foi criado');
        }
    }
}