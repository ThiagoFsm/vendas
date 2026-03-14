<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Projeto - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --sidebar-width: 250px;
        }

        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            transition: background 0.3s;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: #212529;
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            user-select: none;
            /* Transição suave apenas para quando clicar no botão */
            transition: left 0.3s ease;
        }

        .resizer {
            width: 6px;
            cursor: col-resize;
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            background: transparent;
            z-index: 1001;
        }

        .resizer:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Área principal - Expansão de 100% */
        .main-container {
            margin-left: var(--sidebar-width);
            width: 100%;
            padding: 20px;
            flex-grow: 1;
            min-width: 0; /* Evita quebra de layout em tabelas */
            transition: margin-left 0.3s ease;
        }

        /* ESTADO MINIMIZADO (100% de tela) */
        body.sidebar-hidden .sidebar {
            left: calc(var(--sidebar-width) * -1.1); /* Joga a barra para fora */
        }

        body.sidebar-hidden .main-container {
            margin-left: 0; /* Conteúdo ocupa tudo */
        }

        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; white-space: nowrap; overflow: hidden; }
        .sidebar a:hover { background: #343a40; color: white; }

        body.resizing { cursor: col-resize; }
        body.resizing .sidebar, body.resizing .main-container { transition: none; } /* Remove delay enquanto arrasta */

        /* Ajusta o container do Select2 para respeitar o seu design */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 40px !important; /* Ajuste para a altura que você usa nos outros campos */
            border-radius: 8px !important; /* Seu ajuste de design */
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0.75rem !important; /* Alinha com o padding padrão do Bootstrap 5 */
        }

    </style>
</head>
<body id="body-layout">

<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>Vendas</h4>
    </div>
    <ul class="nav flex-column mt-3">
        <li class="nav-item"><a href="/" class="nav-link active">Início</a></li>
        <li class="nav-item"><a href="/pedidos" class="nav-link">Pedidos</a></li>
        <li class="nav-item"><a href="/perfil" class="nav-link">Perfil</a></li>
    </ul>
    <div class="resizer" id="resizer"></div>
</nav>

<main class="main-container" id="main-content">
    <button class="btn btn-dark mb-4" id="toggleSidebar">☰ Menu</button>

    <div id="app">
{{--        <div class="container-fluid px-0">--}}
{{--            @if(session('success'))--}}
{{--                <div class="alert alert-success alert-dismissible fade show" role="alert">--}}
{{--                    <strong>Sucesso!</strong> {{ session('success') }}--}}
{{--                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>--}}
{{--                </div>--}}
{{--            @endif--}}

{{--            @if($errors->any())--}}
{{--                <div class="alert alert-danger alert-dismissible fade show" role="alert">--}}
{{--                    <strong>Ops! Algo deu errado:</strong>--}}
{{--                    <ul class="mb-0 mt-2">--}}
{{--                        @foreach($errors->all() as $error)--}}
{{--                            <li>{{ $error }}</li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
        @yield('content')
    </div>
</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const resizer = document.getElementById('resizer');
    const toggleBtn = document.getElementById('toggleSidebar');
    const body = document.body;

    // --- ARRASTAR (RESIZE) ---
    resizer.addEventListener('mousedown', (e) => {
        e.preventDefault();
        body.classList.add('resizing');
        document.addEventListener('mousemove', resize);
        document.addEventListener('mouseup', stopResize);
    });

    function resize(e) {
        let newWidth = e.clientX;
        if (newWidth > 100 && newWidth < 600) {
            // Atualiza a variável CSS e os estilos inline para garantir precisão
            document.documentElement.style.setProperty('--sidebar-width', newWidth + 'px');
            sidebar.style.width = newWidth + 'px';
            if (!body.classList.contains('sidebar-hidden')) {
                mainContent.style.marginLeft = newWidth + 'px';
            }
        }
    }

    function stopResize() {
        body.classList.remove('resizing');
        document.removeEventListener('mousemove', resize);
    }

    // --- BOTÃO MINIMIZAR (TOGGLE 100%) ---
    toggleBtn.addEventListener('click', () => {
        body.classList.toggle('sidebar-hidden');

        // Ajuste fino: se estiver escondendo, remove a margem inline
        if (body.classList.contains('sidebar-hidden')) {
            mainContent.style.marginLeft = "0px";
        } else {
            // Se estiver abrindo, volta para a largura atual da variável
            const currentWidth = getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width');
            mainContent.style.marginLeft = currentWidth;
        }
    });
</script>
<script>
    window.session = {
        success: "{{ session('success') }}",
        error: "{{ $errors->first() }}"
    };
</script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Dispara se houver mensagem de sucesso
    @if(session('success'))
    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
    });
    @endif

    // Dispara se houver erros de validação
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Ops...',
        html: '{!! implode("<br>", $errors->all()) !!}'
    });
    @endif
</script>
</body>
</html>
