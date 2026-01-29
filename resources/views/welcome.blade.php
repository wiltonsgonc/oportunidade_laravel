<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oportunidades SENAI CIMATEC</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/ico">
    <link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bs-secondary-bg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
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

    <!-- Conteúdo Principal com Classe de Filtro Padrão -->
    <div class="flex-grow filtro-padrao">
        <div class="container my-5 index-content text-light">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <h1 class="index-title">Bem-vindo ao Portal de Oportunidades SENAI CIMATEC</h1>

                    <p class="index-description">
                        O SENAI CIMATEC é o motor da inovação. Aqui, a educação se une à pesquisa e ao desenvolvimento
                        tecnológico para formar profissionais de excelência e gerar soluções que transformam a indústria
                        e a sociedade.
                    </p>

                    <h2 class="index-subtitle">Foco em Excelência e Inovação:</h2>

                    <ul class="index-list">
                        <li><strong>Programas de Pós-Graduação</strong> – Formação de mestres e doutores com visão
                            aplicada e foco em soluções industriais.</li>
                        <li><strong>Vestibular e Graduação</strong> – Cursos que unem prática e tecnologia, formando
                            profissionais prontos para os desafios da nova indústria.</li>
                        <li><strong>Projetos de Inovação e Bolsas</strong> – Chamadas abertas para participação em
                            projetos de P&D e bolsas vinculadas a programas de pesquisa tecnológica.</li>
                    </ul>

                    <h2 class="index-subtitle">Você está interessado em:</h2>

                    <ul class="index-questions">
                        <li>Participar de Projetos de Inovação e contribuir com soluções tecnológicas?</li>
                        <li>Conhecer as oportunidades da Pró-Reitoria de Pós-Graduação e Pesquisa?</li>
                        <li>Ingressar em um curso da Pró-Reitoria de Graduação e transformar sua carreira?</li>
                    </ul>

                    <div class="index-cta text-center mt-5 mb-5">
                        <h3 class="text-light mb-4">Escolha sua área de interesse:</h3>
                        <div class="d-grid gap-2 d-md-block">
                            <a href="{{ route('vagas.home', ['setor' => 'GRADUACAO']) }}" class="btn btn-primary btn-lg mb-2">
                                <i class="bi bi-mortarboard"></i> Graduação e Extensão
                            </a>

                            <a href="{{ route('vagas.home', ['setor' => 'POS_PESQUISA']) }}" class="btn btn-primary btn-lg mb-2">
                                <i class="bi bi-mortarboard-fill"></i> Pós-Graduação e Pesquisa
                            </a>

                            <a href="{{ route('vagas.home', ['setor' => 'AREA_TECNOLOGICA']) }}" class="btn btn-primary btn-lg mb-2">
                                <i class="bi bi-book"></i> Projetos de Inovação
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</body>
</html>