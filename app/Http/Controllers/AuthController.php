<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'senha' => 'required|string',
        ]);
        
        if (Auth::attempt([
            'usuario' => $credentials['usuario'],
            'senha' => $credentials['senha']
        ])) {
            $request->session()->regenerate();
            
            $usuario = Auth::user();
            if ($usuario) {
                $usuario->ultimo_login = now();
                $usuario->ip_ultimo_login = $request->ip();
                $usuario->save();
            }
            
            return redirect()->intended('/dashboard');
        }
        
        return back()->withErrors([
            'usuario' => 'Credenciais inválidas.',
        ])->onlyInput('usuario');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redireciona para a página de login
        return redirect()->route('login');
    }
}