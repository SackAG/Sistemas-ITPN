@extends('layouts.plantilla1')

@section('contenido')
    <div class="container-fluid">
        {{-- Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">
                    <i class="bi bi-gear text-primary"></i>
                    Configuración
                </h2>
                <p class="text-muted mb-0">Administra tu cuenta y preferencias</p>
            </div>
        </div>

        {{-- Tabs de navegación --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.profile') ? 'active' : '' }}" 
                                   wire:navigate
                                   href="{{ route('settings.profile') }}">
                                    <i class="bi bi-person me-2"></i>
                                    Perfil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.password') ? 'active' : '' }}" 
                                   wire:navigate
                                   href="{{ route('settings.password') }}">
                                    <i class="bi bi-key me-2"></i>
                                    Contraseña
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('settings.appearance') ? 'active' : '' }}" 
                                   wire:navigate
                                   href="{{ route('settings.appearance') }}">
                                    <i class="bi bi-palette me-2"></i>
                                    Apariencia
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs {
            border-bottom: none;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background-color: transparent;
            border-bottom: 2px solid #0d6efd;
        }
        
        [data-bs-theme="dark"] .nav-tabs .nav-link {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .nav-tabs .nav-link:hover {
            color: #6ea8fe;
            background-color: rgba(110, 168, 254, 0.1);
        }
        
        [data-bs-theme="dark"] .nav-tabs .nav-link.active {
            color: #6ea8fe;
            border-bottom-color: #6ea8fe;
        }
    </style>
@endsection