<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Projeto - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root {
            --sidebar-width: 140px;
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

        /* Overlay da Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Escurece o fundo */
            backdrop-filter: blur(2px); /* Efeito de desfoque moderno */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000; /* Acima da sidebar (que está em 1000) */
            transition: opacity 0.3s ease;
        }

        /* Container da Modal */
        .modal-content-custom {
            background: #ffffff;
            width: 90%;
            max-width: 800px;
            border-radius: 12px; /* Seguindo a tendência do seu Select2 */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: modalIn 0.3s ease-out;
        }

        /* Header da Modal - Usando o tom da sua Sidebar */
        .modal-header-custom {
            background: #212529; /* Mesma cor da sua sidebar */
            color: #ffffff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-custom h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 500;
        }

        /* Body da Modal */
        .modal-body-custom {
            padding: 20px;
            color: #343a40;
        }

        /* Estilização de inputs dentro da modal para combinar com seu Select2 */
        .modal-body-custom input,
        .modal-body-custom select,
        .modal-body-custom textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            border-radius: 8px; /* Combinando com o seu Select2 */
            margin-top: 5px;
            margin-bottom: 15px;
            outline: none;
            transition: border-color 0.2s;
        }

        .modal-body-custom input:focus {
            border-color: #212529;
        }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            min-width: 300px;
        }

        /* Footer da Modal */
        .modal-footer-custom {
            padding: 8px 12px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Botões */
        .btn-fechar {
            background: #212529;
            color: white;
            border-color: white;
            padding: 5px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-fechar:hover {
            background: #343a40;
            color: white;
        }

        .btn-confirmar {
            background: lightgreen;
            color: black;
            border-color: white;
            padding: 5px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-confirmar:hover {
            background: lightgreen;
            color: black;
        }

        /*botões sabores*/
        .btn-sabor {
            border-color: white;
            padding: 5px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-sabor:hover {
            color: black;
        }

        /* Animação de entrada */
        @keyframes modalIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Força o container do SweetAlert a ficar acima da sua modal */
        .swal2-container {
            z-index: 3000 !important;
        }
    </style>
</head>
<body id="body-layout">

<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>Vendas</h4>
    </div>
    <ul class="nav flex-column mt-3">
        <li class="nav-item"><a href="/" class="nav-link active">Pedidos</a></li>
        <li class="nav-item"><a href="/vendas/producao" class="nav-link">Produção</a></li>
        <li class="nav-item"><a href="/perfil" class="nav-link">Perfil</a></li>
    </ul>
    <div class="resizer" id="resizer"></div>
</nav>

<main class="main-container" id="main-content">
    <button class="btn btn-dark mb-4" id="toggleSidebar">☰ Menu</button>

    <div id="app">
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
    // 1. Tornamos o Toast global para você usar em qualquer lugar (inclusive no Vue)
    window.Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // 2. Dispara se houver mensagem de sucesso do Laravel (Redirect::back()->with('success'))
    @if(session('success'))
    window.Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}",
        timerProgressBar: true,
        showConfirmButton: false
    });
    @endif

    // 3. Dispara se houver erros de validação ($errors)
    @if($errors->any())
    window.Toast.fire({
        icon: 'error',
        title: 'Ops! Algo deu errado',
        text: "{{ $errors->first() }}", // Mostra o primeiro erro
        showConfirmButton: true
    });
    @endif
</script>
</body>
</html>
