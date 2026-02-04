{{-- resources/views/dashboard/index.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/ico">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
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
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6><i class="bi bi-people"></i> Usuários Ativos</h6>
                                <h3>{{ $totalUsuarios }}</h3>
                                <small>Usuários ativos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6><i class="bi bi-briefcase"></i> Vagas Abertas</h6>
                                <h3>{{ $vagasAbertas }}</h3>
                                <small>Vagas disponíveis</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6><i class="bi bi-briefcase-fill"></i> Vagas Encerradas</h6>
                                <h3>{{ $vagasEncerradas }}</h3>
                                <small>Vagas finalizadas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6><i class="bi bi-briefcase"></i> Total de Vagas</h6>
                                <h3>{{ $totalVagas }}</h3>
                                <small>Total cadastrado</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($ultimasVagas->count() > 0)
                <div class="mt-4">
                    <h6>Últimas Vagas Cadastradas</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Status</th>
                                    <th>Criado em</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasVagas as $vaga)
                                <tr>
                                    <td>{{ $vaga->titulo ?? 'Sem título' }}</td>
                                    <td>
                                        @if($vaga->status == 'aberto')
                                            <span class="badge bg-success">Aberto</span>
                                        @elseif($vaga->status == 'encerrado')
                                            <span class="badge bg-danger">Encerrado</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $vaga->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $vaga->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                
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
    
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>