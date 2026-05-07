<?php

return [
    'base_url' => env('KEYCLOAK_BASE_URL'),
    'realm' => env('KEYCLOAK_REALM'),
    'client_id' => env('KEYCLOAK_CLIENT_ID'),
    'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
    'redirect_uri' => env('KEYCLOAK_REDIRECT_URI'),
    'logout_uri' => env('KEYCLOAK_LOGOUT_URI'),
    
    // Modo desenvolvimento (sem Keycloak)
    'dev_mode' => env('KEYCLOAK_DEV_MODE', false),
    'dev_mock_email' => env('KEYCLOAK_DEV_MOCK_EMAIL'),
    'dev_mock_password_hash' => env('KEYCLOAK_DEV_MOCK_PASSWORD_HASH'),

    // Usuário admin (seeder)
    'dev_admin_email' => env('KEYCLOAK_DEV_ADMIN_EMAIL'),
    'dev_admin_password_hash' => env('KEYCLOAK_DEV_ADMIN_PASSWORD_HASH'),
    
    // URLs do Keycloak
    'urls' => [
        'auth' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/auth',
        'token' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/token',
        'userinfo' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/userinfo',
        'logout' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/logout',
    ],
];