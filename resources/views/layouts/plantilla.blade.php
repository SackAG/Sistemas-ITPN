<!DOCTYPE html>
<html lang="es" data-bs-theme="{{ app('settings')['theme'] ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de Asistencia e Inventario</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            background-color: #ffffff;
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            color: #2c3e50;
        }
        .navbar-nav .nav-link {
            color: #5a6c7d;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            color: #0d6efd;
        }
        .btn-outline-primary {
            border-radius: 20px;
            padding: 0.4rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            border-radius: 20px;
            padding: 0.4rem 1.5rem;
            font-weight: 500;
            background-color: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13,110,253,0.3);
        }
        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }
        main {
            min-height: calc(100vh - 200px);
        }
        .theme-toggle {
            background: none;
            border: none;
            color: #5a6c7d;
            padding: 0.5rem;
            cursor: pointer;
            font-size: 1.25rem;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .theme-toggle:hover {
            color: #0d6efd;
            transform: scale(1.1);
        }
        /* Estilos para modo oscuro */
        [data-bs-theme="dark"] .navbar {
            background-color: #1a1d20;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        [data-bs-theme="dark"] .navbar-brand {
            color: #e8eaed;
        }
        [data-bs-theme="dark"] .navbar-nav .nav-link {
            color: #bdc1c6;
        }
        [data-bs-theme="dark"] .navbar-nav .nav-link:hover {
            color: #6ea8fe;
        }
        [data-bs-theme="dark"] .theme-toggle {
            color: #bdc1c6;
        }
        [data-bs-theme="dark"] .theme-toggle:hover {
            color: #6ea8fe;
        }
        /* Footer responsive al tema */
        [data-bs-theme="dark"] .footer {
            background-color: #1a1d20 !important;
            border-top: 1px solid #2d3236;
        }
        [data-bs-theme="dark"] .footer .text-muted {
            color: #8e9297 !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-navbar-png.png') }}" alt="Logo" style="height: auto; width: 100%; max-width: 250px; max-height: 50px;" class="me-2">
                <span>Academi-Track</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Acerca de</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ayuda</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('api.theme') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="theme" value="{{ \App\Models\Setting::get('theme', 'light') === 'dark' ? 'light' : 'dark' }}">
                            <button type="submit" class="theme-toggle" title="Cambiar tema">
                                @if(\App\Models\Setting::get('theme', 'light') === 'dark')
                                    <i class="bi bi-sun-fill"></i>
                                @else
                                    <i class="bi bi-moon-stars-fill"></i>
                                @endif
                            </button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Inicio de sesión</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-primary" href="{{ route('register') }}">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('contenido')
    </main>

    <footer class="footer bg-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 text-muted">© 2025 Control de Asistencia e Inventario. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>