@extends('layouts.plantilla1')

@section('title', 'Panel de Administración')

@section('contenido')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-4">Panel de Administración</h2>
            </div>
        </div>
        
        {{-- Mensaje de error si no tiene permisos --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Grid de estadísticas --}}
        <div class="row g-4 mb-4">
            {{-- Tarjeta: Total Aulas --}}
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="bi bi-building fs-1 text-primary mb-2"></i>
                        <h3 class="display-6 fw-bold">{{ $totalAulas }}</h3>
                        <p class="text-muted mb-0">Total Aulas</p>
                    </div>
                </div>
            </div>

            {{-- Tarjeta: Equipos Operativos --}}
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="bi bi-pc-display fs-1 text-success mb-2"></i>
                        <h3 class="display-6 fw-bold">{{ $totalEquipos }}</h3>
                        <p class="text-muted mb-0">Equipos Operativos</p>
                    </div>
                </div>
            </div>

            {{-- Tarjeta: Grupos Activos --}}
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-1 text-warning mb-2"></i>
                        <h3 class="display-6 fw-bold">{{ $totalGrupos }}</h3>
                        <p class="text-muted mb-0">Grupos Activos</p>
                    </div>
                </div>
            </div>

            {{-- Tarjeta: Total Usuarios --}}
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <i class="bi bi-person-badge fs-1 text-info mb-2"></i>
                        <h3 class="display-6 fw-bold">{{ $totalUsuarios }}</h3>
                        <p class="text-muted mb-0">Total Usuarios</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Desglose de usuarios por rol --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Usuarios por Rol</h3>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3 class="display-4 text-danger">{{ $totalAdmins }}</h3>
                                <p class="text-muted">Administradores</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="display-4 text-primary">{{ $totalProfesores }}</h3>
                                <p class="text-muted">Profesores</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="display-4 text-success">{{ $totalAlumnos }}</h3>
                                <p class="text-muted">Alumnos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enlaces de gestión --}}
        <div class="row g-4">
            <div class="col-md-3">
                <a href="{{ route('admin.aulas.index') }}" class="card text-decoration-none text-center">
                    <div class="card-body">
                        <i class="bi bi-building fs-1 text-primary mb-2"></i>
                        <p class="fw-semibold mb-0">Gestionar Aulas</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="card text-decoration-none text-center">
                    <div class="card-body">
                        <i class="bi bi-pc-display fs-1 text-success mb-2"></i>
                        <p class="fw-semibold mb-0">Gestionar Equipos</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.grupos.index') }}" class="card text-decoration-none text-center">
                    <div class="card-body">
                        <i class="bi bi-people fs-1 text-warning mb-2"></i>
                        <p class="fw-semibold mb-0">Gestionar Grupos</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.users.index') }}" class="card text-decoration-none text-center">
                    <div class="card-body">
                        <i class="bi bi-person-badge fs-1 text-info mb-2"></i>
                        <p class="fw-semibold mb-0">Gestionar Usuarios</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection