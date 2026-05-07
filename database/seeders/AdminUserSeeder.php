<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $email = config('keycloak.dev_admin_email');
        $passwordHash = config('keycloak.dev_admin_password_hash');

        if (!$email) {
            $this->command->error('Configure KEYCLOAK_DEV_ADMIN_EMAIL no .env');
            return;
        }

        if (!$passwordHash) {
            $this->command->error('Configure KEYCLOAK_DEV_ADMIN_PASSWORD_HASH no .env');
            return;
        }

        $this->command->info('=== CRIANDO USUÁRIO ADMINISTRADOR ===');

        DB::table('usuarios')->where('email', $email)->delete();

        $now = Carbon::now();

        $id = DB::table('usuarios')->insertGetId([
            'nome' => 'Administrador',
            'usuario' => 'admin',
            'email' => $email,
            'senha' => $passwordHash,
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
        $this->command->info('E-mail: ' . $email);
    }
}