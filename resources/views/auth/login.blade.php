<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Vagas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 40px 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-height: 70px;
            margin-bottom: 15px;
        }
        .btn-login {
            padding: 10px;
            font-weight: 600;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="{{ asset('assets/img/LOGOTI_2.png') }}" alt="SENAI CIMATEC">
                <h4 class="mt-3">Sistema de Vagas</h4>
                <p class="text-muted">Área Administrativa</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro!</strong> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário</label>
                    <input type="text" 
                           class="form-control @error('usuario') is-invalid @enderror" 
                           id="usuario" 
                           name="usuario" 
                           value="{{ old('usuario') }}" 
                           required 
                           autofocus
                           placeholder="Digite seu usuário">
                    @error('usuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" 
                           class="form-control @error('senha') is-invalid @enderror" 
                           id="senha" 
                           name="senha" 
                           required
                           placeholder="Digite sua senha">
                    @error('senha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">Entrar</button>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="text-muted small mb-0">Entre com suas credenciais administrativas</p>
                    <p class="text-muted small">Entre em contato com o administrador se precisar de acesso</p>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus no campo de usuário
        document.getElementById('usuario').focus();
        
        // Limpar mensagens de erro após 5 segundos
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>