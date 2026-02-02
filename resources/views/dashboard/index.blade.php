{{-- resources/views/dashboard/index.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                @auth
                    <span class="navbar-text me-3">
                        Olá, {{ auth()->user()->nome ?? auth()->user()->usuario }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-house"></i> Dashboard Administrativo
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h6>Bem-vindo ao Sistema de Vagas SENAI CIMATEC!</h6>
                    <p class="mb-0">Sistema em desenvolvimento. Esta é a área administrativa.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6><i class="bi bi-people"></i> Usuários</h6>
                                <h3>1</h3>
                                <small>Admin cadastrado</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6><i class="bi bi-briefcase"></i> Vagas</h6>
                                <h3>0</h3>
                                <small>Vagas cadastradas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6><i class="bi bi-clock-history"></i> Status</h6>
                                <h3>Sistema</h3>
                                <small>Em desenvolvimento</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Links Rápidos</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                        <a href="{{ route('password.change') }}" class="btn btn-warning">
                            <i class="bi bi-key"></i> Alterar Senha
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary" target="_blank">
                            <i class="bi bi-globe"></i> Site Público
                        </a>
                        @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center text-muted">
            <small>Sistema de Vagas SENAI CIMATEC - Versão de Desenvolvimento</small>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>