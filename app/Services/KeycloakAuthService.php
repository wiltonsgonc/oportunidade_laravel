<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

class KeycloakAuthService
{
    protected ?Keycloak $provider = null;

    /**
     * Verificar se está em modo desenvolvimento (sem Keycloak)
     */
    public function isDevelopmentMode(): bool
    {
        return filter_var(env('KEYCLOAK_DEV_MODE', false), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Obter o provider Keycloak (apenas em modo produção)
     */
    protected function getProvider(): Keycloak
    {
        if ($this->isDevelopmentMode()) {
            throw new \RuntimeException('Não é possível usar Keycloak em modo desenvolvimento');
        }

        if ($this->provider === null) {
            $this->provider = new Keycloak([
                'authServerUrl' => config('keycloak.base_url'),
                'realm' => config('keycloak.realm'),
                'clientId' => config('keycloak.client_id'),
                'clientSecret' => config('keycloak.client_secret'),
                'redirectUri' => config('keycloak.redirect_uri'),
            ]);
        }

        return $this->provider;
    }

    /**
     * Obter URL de autorização
     */
    public function getAuthorizationUrl(): string
    {
        $provider = $this->getProvider();

        $options = [
            'scope' => ['openid', 'profile', 'email'],
        ];

        return $provider->getAuthorizationUrl($options);
    }

    /**
     * Trocar código de autorização por tokens
     */
    public function exchangeCodeForTokens(string $code): array
    {
        $provider = $this->getProvider();

        try {
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $code,
            ]);

            return [
                'access_token' => $accessToken->getToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'expires_in' => $accessToken->getExpires(),
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao trocar código por tokens', [
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Erro na autenticação: ' . $e->getMessage());
        }
    }

    /**
     * Obter informações do usuário
     */
    public function getUserInfo(string $accessToken): array
    {
        try {
            $userInfoUrl = config('keycloak.urls.userinfo');
            
            $response = Http::withToken($accessToken)
                ->get($userInfoUrl);

            if ($response->failed()) {
                throw new \RuntimeException('Falha ao obter userinfo');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erro ao obter informações do usuário', [
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Erro ao obter dados do usuário: ' . $e->getMessage());
        }
    }

    /**
     * Just-In-Time Provisioning - criar/atualizar usuário no primeiro login
     */
    public function jitProvision(array $userInfo): Usuario
    {
        // Normalizar dados do Keycloak
        $email = $userInfo['email'] ?? null;
        $sub = $userInfo['sub'] ?? null; // UUID do Keycloak
        $nome = $userInfo['name'] ?? $userInfo['preferred_username'] ?? 'Usuário';
        $usuario = $userInfo['preferred_username'] ?? $userInfo['email'] ?? 'usuario';

        if (!$email) {
            throw new \RuntimeException('E-mail não fornecido pelo IdP');
        }

        // Buscar usuário existente
        $usuarioModel = Usuario::where('email', $email)->first();

        // Mapear roles do Keycloak
        $roles = $this->mapRoles($userInfo);

        if ($usuarioModel) {
            // Atualizar dados existentes
            $usuarioModel->update([
                'nome' => $nome,
                'usuario' => $usuario,
                'is_admin' => $roles['is_admin'],
                'is_admin_principal' => $roles['is_admin_principal'],
                'ativo' => true,
            ]);

            Log::info('Usuário atualizado via JIT', ['email' => $email]);
        } else {
            // Criar novo usuário
            $usuarioModel = Usuario::create([
                'nome' => $nome,
                'usuario' => $usuario,
                'email' => $email,
                'senha' => '', // Não usada com SSO
                'is_admin' => $roles['is_admin'],
                'is_admin_principal' => $roles['is_admin_principal'],
                'ativo' => true,
            ]);

            Log::info('Usuário criado via JIT', ['email' => $email]);
        }

        return $usuarioModel;
    }

    /**
     * Mapear roles do Keycloak para permissões da aplicação
     */
    protected function mapRoles(array $userInfo): array
    {
        $isAdmin = false;
        $isAdminPrincipal = false;

        // Verificar roles no token
        $roles = $userInfo['roles'] ?? $userInfo['realm_access']['roles'] ?? [];

        // Mapeamento de roles do Keycloak para a aplicação
        $roleMapping = [
            'vagas-admin' => 'is_admin',
            'vagas-admin-principal' => 'is_admin_principal',
            'admin' => 'is_admin',
            'administrator' => 'is_admin',
        ];

        foreach ($roles as $role) {
            if (isset($roleMapping[$role])) {
                if ($roleMapping[$role] === 'is_admin_principal') {
                    $isAdminPrincipal = true;
                    $isAdmin = true;
                } elseif ($roleMapping[$role] === 'is_admin') {
                    $isAdmin = true;
                }
            }
        }

        return [
            'is_admin' => $isAdmin,
            'is_admin_principal' => $isAdminPrincipal,
        ];
    }

    /**
     * Obter URL de logout
     */
    public function getLogoutUrl(): string
    {
        $baseUrl = config('keycloak.base_url');
        $realm = config('keycloak.realm');
        $clientId = config('keycloak.client_id');

        $idToken = session('keycloak_id_token', '');

        $url = "{$baseUrl}/realms/{$realm}/protocol/openid-connect/logout?"
            . "client_id={$clientId}"
            . "&post_logout_redirect_uri=" . urlencode(config('app.url', url('/')));

        if ($idToken) {
            $url .= "&id_token_hint=" . $idToken;
        }

        return $url;
    }

    /**
     * Validar token JWT
     */
    public function validateToken(string $accessToken): bool
    {
        try {
            $userInfoUrl = config('keycloak.urls.userinfo');
            
            $response = Http::withToken($accessToken)
                ->get($userInfoUrl);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
