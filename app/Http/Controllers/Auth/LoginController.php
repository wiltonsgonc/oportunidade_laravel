<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class LoginController extends Controller
{
    /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'senha' => ['required'],
        ]);

        // Verificar se é email ou nome de usuário
        $field = filter_var($credentials['usuario'], FILTER_VALIDATE_EMAIL) ? 'email' : 'usuario';
        
        // Buscar usuário
        $usuario = Usuario::where($field, $credentials['usuario'])
                          ->where('ativo', true)
                          ->first();

        if ($usuario && password_verify($credentials['senha'], $usuario->senha)) {
            // Logar manualmente
            Auth::guard('web')->login($usuario);
            
            // Atualizar último login
            $usuario->ultimo_login = now();
            $usuario->ip_ultimo_login = $request->ip();
            $usuario->save();
            
            $request->session()->regenerate();
            
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'usuario' => 'Credenciais inválidas ou usuário inativo.',
        ])->onlyInput('usuario');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}