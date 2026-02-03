<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SENAI CIMATEC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .login-logo {
            max-width: 200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card p-5">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/img/LOGOTI_2.png') }}" alt="SENAI CIMATEC" class="login-logo">
                        <h3 class="mt-3">Sistema de Vagas</h3>
                        <p class="text-muted">Área Administrativa</p>
                    </div>

                    <!-- Formulário de Login -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Senha -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botão Login -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Entrar
                            </button>
                        </div>

                        <!-- Mensagens de erro -->
                        @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                    </form>

                    <!-- Link de retorno -->
                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Voltar para o site
                        </a>
                    </div>
                </div>

                <!-- Informações de acesso para desenvolvimento -->
                @if(app()->environment('local') || app()->environment('development'))
                <div class="card mt-3 border-info">
                    <div class="card-body text-center">
                        <small class="text-muted">
                            <strong>Desenvolvimento</strong><br>
                            Usuário: admin@senai.com<br>
                            Senha: password
                        </small>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>