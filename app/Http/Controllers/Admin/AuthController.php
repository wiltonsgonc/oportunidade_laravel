<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Mostrar formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    // Processar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'senha' => 'required|string',
        ]);
        
        // Tentar autenticar com o campo 'usuario' e 'senha'
        if (Auth::attempt([
            'usuario' => $credentials['usuario'],
            'senha' => $credentials['senha']
        ])) {
            $request->session()->regenerate();
            
            // Atualizar último login
            $usuario = Auth::user();
            $usuario->ultimo_login = now();
            $usuario->ip_ultimo_login = $request->ip();
            $usuario->save();
            
            return redirect()->intended('/dashboard');
        }
        
        return back()->withErrors([
            'usuario' => 'Credenciais inválidas.',
        ])->onlyInput('usuario');
    }
    
    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}