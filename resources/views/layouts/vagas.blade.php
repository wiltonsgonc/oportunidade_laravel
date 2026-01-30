<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Oportunidades SENAI CIMATEC')</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        /* Classes de filtro para imagens de fundo */
        .filtro-padrao {
            background-image: url("{{ asset('assets/img/background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: calc(100vh - 200px);
        }

        .filtro-tecnologico {
            background-image: url("{{ asset('assets/img/filtro_tecnologico.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: calc(100vh - 200px);
        }

        .filtro-pos-pesquisa {
            background-image: url("{{ asset('assets/img/filtro_pos_pesquisa.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: calc(100vh - 200px);
        }

        .filtro-graduacao {
            background-image: url("{{ asset('assets/img/filtro_graduacao.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: calc(100vh - 200px);
        }

        /* Estilos gerais */
        .flex-grow {
            flex-grow: 1;
        }

        .text-justify-custom {
            text-align: justify;
        }

        /* Ajustes para os cards */
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* Responsividade da tabela */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bs-secondary-bg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <img src="{{ asset('assets/img/LOGOTI_2.png') }}" alt="UNIVERSIDADE SENAI CIMATEC" height="60"
                    class="d-inline-block align-top">
            </a>

            <!-- Texto centralizado -->
            <div class="navbar-text mx-auto d-none d-md-block">
                <span class="fs-4 text-primary fw-bold">
                    Conecte-se ao Futuro: Editais, Bolsas e Programas
                </span>
            </div>

            <!-- Botão do admin -->
            <div class="d-flex">
                <a href="{{ route('admin.login') }}" target="_blank" class="btn btn-primary text-nowrap bi bi-person">
                    Área do Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-primary text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0"> {{ date('Y') }} SENAI CIMATEC</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>