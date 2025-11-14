@extends('layouts.plantilla1')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-person-badge text-primary"></i> Reporte Individual de Asistencia
            </h1>
            <p class="text-muted mb-0">{{ $alumno->name }}</p>
        </div>
        <div>
            <a href="{{ route('profesor.asistencias.historial', $grupo->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Historial
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Información del Alumno y Grupo -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i> Información del Alumno
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="text-muted mb-1">Nombre Completo</h6>
                            <p class="mb-0 fw-semibold fs-5">{{ $alumno->name }}</p>
                        </div>
                        <div class="col-6 mb-2">
                            <h6 class="text-muted mb-1">Número de Control</h6>
                            <p class="mb-0">
                                <span class="badge bg-secondary fs-6">{{ $alumno->numero_control ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="col-6 mb-2">
                            <h6 class="text-muted mb-1">Email</h6>
                            <p class="mb-0">{{ $alumno->email }}</p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted mb-1">Carrera</h6>
                            <p class="mb-0">{{ $alumno->carrera->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-book"></i> Información del Grupo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <h6 class="text-muted mb-1">Grupo</h6>
                            <p class="mb-0 fw-semibold">{{ $grupo->clave_grupo }}</p>
                        </div>
                        <div class="col-6 mb-2">
                            <h6 class="text-muted mb-1">Periodo</h6>
                            <p class="mb-0 fw-semibold">{{ $grupo->periodo }} {{ $grupo->año }}</p>
                        </div>
                        <div class="col-12 mb-2">
                            <h6 class="text-muted mb-1">Materia</h6>
                            <p class="mb-0">{{ $grupo->materia->nombre }}</p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted mb-1">Profesor</h6>
                            <p class="mb-0">{{ $grupo->profesor->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Asistencia -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-dark">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check text-dark" style="font-size: 2.5rem;"></i>
                    <h2 class="mt-2 mb-0 text-dark">{{ $estadisticas['total_clases'] }}</h2>
                    <p class="text-muted mb-0">Total de Clases</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                    <h2 class="mt-2 mb-0 text-success">{{ $estadisticas['presentes'] }}</h2>
                    <p class="text-muted mb-0">Presentes</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 2.5rem;"></i>
                    <h2 class="mt-2 mb-0 text-danger">{{ $estadisticas['ausentes'] }}</h2>
                    <p class="text-muted mb-0">Ausentes</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-clock-fill text-warning" style="font-size: 2.5rem;"></i>
                    <h2 class="mt-2 mb-0 text-warning">{{ $estadisticas['retardos'] }}</h2>
                    <p class="text-muted mb-0">Retardos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Porcentaje de Asistencia -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-3">Porcentaje de Asistencia</h5>
                    @php
                        $porcentaje = $estadisticas['porcentaje_asistencia'];
                        if ($porcentaje >= 90) {
                            $colorProgress = 'bg-success';
                            $estado = 'Excelente';
                            $icono = 'bi-emoji-smile-fill text-success';
                        } elseif ($porcentaje >= 80) {
                            $colorProgress = 'bg-info';
                            $estado = 'Bueno';
                            $icono = 'bi-emoji-smile text-info';
                        } elseif ($porcentaje >= 70) {
                            $colorProgress = 'bg-warning';
                            $estado = 'Regular';
                            $icono = 'bi-emoji-neutral text-warning';
                        } else {
                            $colorProgress = 'bg-danger';
                            $estado = 'Insuficiente';
                            $icono = 'bi-emoji-frown text-danger';
                        }
                    @endphp
                    <div class="progress" style="height: 40px;">
                        <div class="progress-bar {{ $colorProgress }}" 
                             role="progressbar" 
                             style="width: {{ $porcentaje }}%; font-size: 1.25rem; font-weight: 600;" 
                             aria-valuenow="{{ $porcentaje }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($porcentaje, 2) }}%
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi {{ $icono }}" style="font-size: 3rem;"></i>
                    <h4 class="mt-2 mb-0">{{ $estado }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen Adicional -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-info">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check text-info" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0 text-info">{{ $estadisticas['justificados'] }}</h3>
                    <p class="text-muted mb-0">Ausencias Justificadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-secondary">
                <div class="card-body text-center">
                    <i class="bi bi-calculator text-secondary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0 text-secondary">
                        {{ $estadisticas['presentes'] + $estadisticas['retardos'] }}
                    </h3>
                    <p class="text-muted mb-0">Asistencias Efectivas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial Detallado -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-clock-history"></i> Historial Detallado de Asistencias
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 150px;">Fecha</th>
                            <th style="width: 100px;">Día</th>
                            <th class="text-center" style="width: 150px;">Estado</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asistencias as $index => $asistencia)
                            @php
                                $diasSemana = [
                                    'Sunday' => 'Domingo',
                                    'Monday' => 'Lunes',
                                    'Tuesday' => 'Martes',
                                    'Wednesday' => 'Miércoles',
                                    'Thursday' => 'Jueves',
                                    'Friday' => 'Viernes',
                                    'Saturday' => 'Sábado'
                                ];
                                $diaSemana = $diasSemana[date('l', strtotime($asistencia->fecha))];
                            @endphp
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">
                                    <strong>{{ date('d/m/Y', strtotime($asistencia->fecha)) }}</strong>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-secondary">{{ $diaSemana }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    @if($asistencia->estado === 'presente')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Presente
                                        </span>
                                    @elseif($asistencia->estado === 'ausente')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Ausente
                                        </span>
                                    @elseif($asistencia->estado === 'retardo')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i> Retardo
                                        </span>
                                    @elseif($asistencia->estado === 'justificado')
                                        <span class="badge bg-info">
                                            <i class="bi bi-shield-check"></i> Justificado
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($asistencia->observaciones)
                                        <small>{{ $asistencia->observaciones }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2 mb-0">No hay registros de asistencia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('profesor.asistencias.historial', $grupo->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Historial
        </a>
        <button class="btn btn-success" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimir Reporte
        </button>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 0.5rem;
    }
    
    .progress-bar {
        line-height: 40px;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
    
    @media print {
        .btn,
        .top-bar,
        .sidebar {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }
        
        .card {
            break-inside: avoid;
        }
    }
    
    @media (max-width: 768px) {
        .row .col-md-3,
        .row .col-md-6 {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Funcionalidad adicional si se necesita
</script>
@endpush
@endsection
