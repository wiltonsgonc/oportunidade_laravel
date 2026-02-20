<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\KeycloakAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected KeycloakAuthService $keycloakService
    ) {}

    /**
     * Redirecionar para Keycloak (ou mock em desenvolvimento)
     */
    public function redirect(): RedirectResponse
    {
        if ($this->keycloakService->isDevelopmentMode()) {
            return $this->handleDevelopmentLogin();
        }

        $authorizationUrl = $this->keycloakService->getAuthorizationUrl();
        return redirect($authorizationUrl);
    }

    /**
     * Callback do Keycloak - processa o código de autorização
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $code = $request->get('code');

            if (!$code) {
                Log::error('Keycloak callback sem código de autorização');
                return redirect()->route('login')->with('error', 'Erro na autenticação: código não fornecido');
            }

            // Trocar código por tokens
            $tokens = $this->keycloakService->exchangeCodeForTokens($code);

            // Obter informações do usuário
            $userInfo = $this->keycloakService->getUserInfo($tokens['access_token']);

            // Criar/atualizar usuário via JIT
            $usuario = $this->keycloakService->jitProvision($userInfo);

            // Fazer login
            Auth::login($usuario);

            Log::info('Usuário logado via Keycloak', ['email' => $userInfo['email'] ?? 'unknown']);

            return redirect()->intended(route('dashboard'))->with('success', 'Bem-vindo!');

        } catch (\Exception $e) {
            Log::error('Erro no callback Keycloak: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Erro na autenticação: ' . $e->getMessage());
        }
    }

    /**
     * Logout - encerra sessão local e no Keycloak (Single Logout)
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            Log::info('Usuário realizou logout', ['email' => $user->email]);
        }

        // Se não estiver em modo desenvolvimento, redireciona para logout do Keycloak
        if (!$this->keycloakService->isDevelopmentMode()) {
            $logoutUrl = $this->keycloakService->getLogoutUrl();
            return redirect($logoutUrl);
        }

        return redirect()->route('login');
    }

    /**
     * Tratamento de erro na autenticação
     */
    public function error(Request $request): View
    {
        $error = $request->get('error', 'Erro desconhecido');
        $errorDescription = $request->get('error_description', '');

        return view('auth.login-error', [
            'error' => $error,
            'errorDescription' => $errorDescription
        ]);
    }

    /**
     * Simulação de login para desenvolvimento (Mock)
     */
    protected function handleDevelopmentLogin(): RedirectResponse
    {
        $developmentEmail = config('keycloak.dev_mock_email', 'admin@cimatec.edu.br');

        // Buscar usuário existente ou criar mock
        $usuario = Usuario::where('email', $developmentEmail)->first();

        if (!$usuario) {
            // Criar usuário mock para desenvolvimento
            $usuario = Usuario::create([
                'nome' => 'Desenvolvedor',
                'usuario' => 'dev',
                'email' => $developmentEmail,
                'senha' => '', // Não usada em mock
                'is_admin' => true,
                'is_admin_principal' => true,
                'ativo' => true,
            ]);
        }

        Auth::login($usuario);

        Log::info('Login em modo desenvolvimento', ['email' => $usuario->email]);

        return redirect()->intended(route('dashboard'))->with('success', 'Modo desenvolvimento: login automático');
    }

    /**
     * Selecionar usuário para login em modo desenvolvimento
     */
    public function developmentUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$this->keycloakService->isDevelopmentMode()) {
            return response()->json(['error' => 'Modo de desenvolvimento não habilitado'], 403);
        }

        $usuarios = Usuario::where('ativo', true)
            ->select('id', 'nome', 'email', 'is_admin', 'is_admin_principal')
            ->get();

        return response()->json($usuarios);
    }

    /**
     * Login como usuário específico (modo desenvolvimento)
     */
    public function loginAs(Request $request, int $userId): RedirectResponse
    {
        if (!$this->keycloakService->isDevelopmentMode()) {
            abort(403, 'Apenas disponível em modo de desenvolvimento');
        }

        $usuario = Usuario::findOrFail($userId);

        if (!$usuario->ativo) {
            return redirect()->route('login')->with('error', 'Usuário inativo');
        }

        Auth::login($usuario);

        Log::info('Login como usuário (dev mode)', [
            'email' => $usuario->email,
            'as' => Auth::user()->email
        ]);

        return redirect()->intended(route('dashboard'))->with('success', 'Logado como: ' . $usuario->nome);
    }
}
