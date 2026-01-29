<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        // Implementação temporária - será melhorada
        return redirect()->route('admin.dashboard');
    }
}