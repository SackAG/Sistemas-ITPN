@extends('layouts.plantilla1')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-people-fill text-primary"></i> Mis Grupos
            </h1>
            <p class="text-muted mb-0">Gestiona la asistencia de tus grupos asignados</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0">{{ $grupos->count() }}</h3>
                    <p class="text-muted mb-0">Grupos Asignados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="bi bi-person-check-fill text-success" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0">{{ $grupos->sum('cantidad_alumnos') }}</h3>
                    <p class="text-muted mb-0">Total de Alumnos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-info">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check text-info" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0">{{ date('d/m/Y') }}</h3>
                    <p class="text-muted mb-0">Fecha Actual</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-book text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0">{{ $grupos->where('activo', true)->count() }}</h3>
                    <p class="text-muted mb-0">Grupos Activos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Grupos -->
    <div class="row">
        @forelse($grupos as $grupo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 hover-shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-bookmark-fill"></i> {{ $grupo->clave_grupo }}
                            </h5>
                            @if($grupo->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="bi bi-book"></i> {{ $grupo->materia->nombre }}
                        </h6>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">
                                    <i class="bi bi-people"></i> Alumnos:
                                </span>
                                <strong>{{ $grupo->cantidad_alumnos }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">
                                    <i class="bi bi-calendar3"></i> Periodo:
                                </span>
                                <strong>{{ $grupo->periodo }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">
                                    <i class="bi bi-calendar-event"></i> Año:
                                </span>
                                <strong>{{ $grupo->año }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-grid gap-2">
                            <a href="{{ route('profesor.asistencias.pasar-lista', $grupo->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-clipboard-check"></i> Pasar Lista
                            </a>
                            <a href="{{ route('profesor.asistencias.historial', $grupo->id) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-clock-history"></i> Ver Historial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #6c757d;"></i>
                        <h4 class="mt-3 text-muted">No tienes grupos asignados</h4>
                        <p class="text-muted">Ponte en contacto con el administrador para que te asigne grupos.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .card-footer {
        border-top: 1px solid #e5e7eb;
    }
    
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .col-md-6 {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush
@endsection
