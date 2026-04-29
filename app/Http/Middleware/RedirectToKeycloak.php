<?php

namespace App\Http\Middleware;

use App\Services\KeycloakAuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectToKeycloak
{
    protected KeycloakAuthService $keycloakService;

    public function __construct(KeycloakAuthService $keycloakService)
    {
        $this->keycloakService = $keycloakService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário não está autenticado
        if (!Auth::check()) {
            // Se já está na rota de login ou callback, permite o acesso
            if ($request->routeIs('login') ||
                $request->routeIs('auth.callback') ||
                $request->routeIs('logout') ||
                $request->routeIs('auth.error')) {
                return $next($request);
            }

            // Redireciona para o Keycloak (ou login mock em desenvolvimento)
            return redirect()->route('login');
        }

        return $next($request);
    }
}