<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Mostrar formulário de login
     */
    public function showLoginForm()
    {
        // Se já estiver autenticado, redireciona para dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        // Se já estiver autenticado, redireciona
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Validação
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Determinar se é email ou nome de usuário
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'usuario';
        
        // Buscar usuário ativo
        $usuario = Usuario::where($field, $request->email)
                          ->where('ativo', true)
                          ->first();

        if (!$usuario) {
            return back()->withErrors([
                'email' => 'Usuário não encontrado ou inativo.',
            ])->onlyInput('email');
        }

        // Verificar senha
        if (!Hash::check($request->password, $usuario->senha)) {
            return back()->withErrors([
                'email' => 'Credenciais incorretas.',
            ])->onlyInput('email');
        }

        // Logar usando o guard correto
        Auth::guard('web')->login($usuario);
        
        // Atualizar último login
        $usuario->ultimo_login = now();
        $usuario->ip_ultimo_login = $request->ip();
        $usuario->save();
        
        $request->session()->regenerate();
        
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}