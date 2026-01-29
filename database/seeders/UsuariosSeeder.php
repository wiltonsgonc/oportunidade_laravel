<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // Usuário admin com senha original 'senai_admin_2025'
        Usuario::create([
            'usuario' => 'admin',
            'email' => 'admin@cimatec.com.br',
            'senha' => '$2y$10$BS/elUBOtTRD.p8zkhUz0.IDTcLNWY2l0gRTbvq5Knl8mXv5pEGaS',
            'is_admin' => true,
        ]);
    }
}