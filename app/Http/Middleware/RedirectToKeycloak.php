<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectToKeycloak
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário não está autenticado
        if (!Auth::check()) {
            // Se já está na rota de login ou callback, permite o acesso
            if ($request->routeIs('admin.login') || 
                $request->routeIs('admin.auth.callback') ||
                $request->routeIs('admin.logout') ||
                $request->routeIs('admin.login.error')) {
                return $next($request);
            }
            
            // Redireciona para o Keycloak
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}