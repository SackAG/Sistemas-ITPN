<!DOCTYPE html>
<html lang="es" data-bs-theme="{{ app('settings')['theme'] ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de Asistencia e Inventario - Sistema</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        body {
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background-color: #ffffff;
            box-shadow: 2px 0 4px rgba(0,0,0,0.08);
            padding: 1.5rem 0 0 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }
        
        .sidebar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            color: #2c3e50;
            text-decoration: none;
        }
        
        .sidebar-nav {
            padding: 0 1rem 1rem 1rem;
            overflow-y: auto;
            flex: 1;
            max-height: calc(100vh - 200px);
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        .sidebar-nav .nav-link {
            color: #5a6c7d;
            font-weight: 500;
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: #f3f4f6;
            color: #0d6efd;
        }
        
        .sidebar-nav .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            background-color: #f9fafb;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
        }
        
        .user-details {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2c3e50;
            margin: 0;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0;
        }
        
        /* Main content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }
        
        /* Top bar */
        .top-bar {
            background-color: #ffffff;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        
        .theme-toggle {
            background: none;
            border: none;
            color: #5a6c7d;
            padding: 0.5rem;
            cursor: pointer;
            font-size: 1.25rem;
            transition: color 0.3s ease, transform 0.3s ease;
            border-radius: 8px;
        }
        
        .theme-toggle:hover {
            color: #0d6efd;
            background-color: #f3f4f6;
        }
        
        .content-wrapper {
            padding: 2rem 1.5rem;
        }
        
        /* Dark mode */
        [data-bs-theme="dark"] .sidebar {
            background-color: #1a1d20;
            box-shadow: 2px 0 4px rgba(0,0,0,0.3);
        }
        
        [data-bs-theme="dark"] .sidebar-header {
            border-bottom-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .sidebar-brand {
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .sidebar-nav .nav-link {
            color: #bdc1c6;
        }
        
        [data-bs-theme="dark"] .sidebar-nav .nav-link:hover {
            background-color: #2d3236;
            color: #6ea8fe;
        }
        
        [data-bs-theme="dark"] .sidebar-nav .nav-link.active {
            background-color: #1e3a5f;
            color: #6ea8fe;
        }
        
        [data-bs-theme="dark"] .sidebar-footer {
            border-top-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .user-info {
            background-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .user-name {
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .user-role {
            color: #8e9297;
        }
        
        [data-bs-theme="dark"] .top-bar {
            background-color: #1a1d20;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        [data-bs-theme="dark"] .theme-toggle {
            color: #bdc1c6;
        }
        
        [data-bs-theme="dark"] .theme-toggle:hover {
            color: #6ea8fe;
            background-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .main-content {
            background-color: #0d1117;
            color: #e8eaed;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a class="navbar-brand d-flex align-items-center justify-content-center" href="#">
                <img src="{{ asset('images/logo-navbar-png.png') }}" alt="Logo" style="width: 100%; height: auto; max-width: 260px;">
                {{-- <span>Control de Asistencia</span> --}}
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i>
                        Dashboard
                    </a>
                </li>

                {{-- Menú para Administradores --}}
                @if(auth()->user()->rol === 'admin')
                    {{-- Sección: Gestión Académica --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Gestión Académica</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.carreras.*') ? 'active' : '' }}" href="{{ route('admin.carreras.index') }}">
                            <i class="bi bi-mortarboard"></i>
                            Carreras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.materias.*') ? 'active' : '' }}" href="{{ route('admin.materias.index') }}">
                            <i class="bi bi-book"></i>
                            Materias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.grupos.*') ? 'active' : '' }}" href="{{ route('admin.grupos.index') }}">
                            <i class="bi bi-people-fill"></i>
                            Grupos
                        </a>
                    </li>

                    {{-- Sección: Infraestructura --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Infraestructura</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.aulas.*') ? 'active' : '' }}" href="{{ route('admin.aulas.index') }}">
                            <i class="bi bi-building"></i>
                            Aulas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.equipos.*') ? 'active' : '' }}" href="{{ route('admin.equipos.index') }}">
                            <i class="bi bi-pc-display"></i>
                            Equipos
                        </a>
                    </li>

                    {{-- Sección: Asignaciones --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Asignaciones</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.asignaciones.*') ? 'active' : '' }}" href="{{ route('admin.asignaciones.index') }}">
                            <i class="bi bi-calendar-week"></i>
                            Horarios de Aulas
                        </a>
                    </li>

                    {{-- Sección: Usuarios --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Usuarios</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-person-badge"></i>
                            Gestión de Usuarios
                        </a>
                    </li>

                    {{-- Sección: Reportes --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Reportes</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.asistencias') ? 'active' : '' }}" href="#">
                            <i class="bi bi-clipboard-data"></i>
                            Reporte de Asistencias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.equipos') ? 'active' : '' }}" href="#">
                            <i class="bi bi-bar-chart"></i>
                            Uso de Equipos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reportes.aulas') ? 'active' : '' }}" href="#">
                            <i class="bi bi-pie-chart"></i>
                            Ocupación de Aulas
                        </a>
                    </li>
                @endif

                {{-- Menú para Profesores --}}
                @if(auth()->user()->rol === 'profesor')
                    {{-- Mis Clases --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Mis Clases</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.dashboard') ? 'active' : '' }}" href="{{ route('profesor.dashboard') }}">
                            <i class="bi bi-people-fill"></i>
                            Mis Grupos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.horarios.*') ? 'active' : '' }}" href="#">
                            <i class="bi bi-calendar-week"></i>
                            Mi Horario
                        </a>
                    </li>
                    
                    {{-- Asistencias --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Asistencias</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.asistencias.*') ? 'active' : '' }}" href="{{ route('profesor.dashboard') }}">
                            <i class="bi bi-clipboard-check"></i>
                            Gestionar Asistencias
                        </a>
                    </li>

                    {{-- Equipos --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Equipos</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.equipos.reservar') ? 'active' : '' }}" href="#">
                            <i class="bi bi-calendar-plus"></i>
                            Reservar Equipos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profesor.equipos.mis-reservaciones') ? 'active' : '' }}" href="#">
                            <i class="bi bi-list-check"></i>
                            Mis Reservaciones
                        </a>
                    </li>
                @endif

                {{-- Menú para Alumnos --}}
                @if(auth()->user()->rol === 'alumno')
                    {{-- Mi Información --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Mi Información</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('alumno.horario') ? 'active' : '' }}" href="#">
                            <i class="bi bi-calendar-week"></i>
                            Mi Horario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('alumno.asistencias') ? 'active' : '' }}" href="#">
                            <i class="bi bi-clipboard-data"></i>
                            Mis Asistencias
                        </a>
                    </li>

                    {{-- Equipos --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Equipos</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('alumno.equipos.solicitar') ? 'active' : '' }}" href="#">
                            <i class="bi bi-laptop"></i>
                            Solicitar Uso Libre
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('alumno.equipos.mis-usos') ? 'active' : '' }}" href="#">
                            <i class="bi bi-clock-history"></i>
                            Mis Usos de Equipos
                        </a>
                    </li>
                @endif

                {{-- Configuración (para todos) --}}
                <li class="nav-item mt-3">
                    <small class="text-muted text-uppercase px-3" style="font-size: 0.75rem;">Sistema</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.profile') }}">
                        <i class="bi bi-gear"></i>
                        Configuración
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="user-details">
                    <p class="user-name">{{ auth()->user()->name ?? 'Usuario' }}</p>
                    <p class="user-role">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm" title="Cerrar sesión">
                        <i class="bi bi-box-arrow-right text-danger"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('api.theme') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="theme" value="{{ app('settings')['theme'] === 'dark' ? 'light' : 'dark' }}">
                    <button type="submit" class="theme-toggle" title="Cambiar tema">
                        @if(app('settings')['theme'] === 'dark')
                            <i class="bi bi-sun-fill"></i>
                        @else
                            <i class="bi bi-moon-stars-fill"></i>
                        @endif
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            @yield('contenido')
        </div>
    </div>
    @stack('scripts')
</body>
</html>