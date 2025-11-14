@extends('layouts.plantilla1')

@section('title', 'Historial de Asistencias')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Historial de Asistencias</h1>
            <p class="text-muted mb-0">{{ $grupo->clave_grupo }} - {{ $grupo->materia->nombre }}</p>
        </div>
        <div>
            <a href="{{ route('profesor.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Mis Grupos
            </a>
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

    <!-- Información del Grupo -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h6 class="text-muted mb-1">Grupo</h6>
                    <p class="mb-0 fw-semibold">{{ $grupo->clave_grupo }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-muted mb-1">Materia</h6>
                    <p class="mb-0 fw-semibold">{{ $grupo->materia->nombre }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-muted mb-1">Periodo</h6>
                    <p class="mb-0 fw-semibold">{{ $grupo->periodo }} {{ $grupo->año }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-muted mb-1">Clases Impartidas</h6>
                    <p class="mb-0 fw-semibold">{{ $fechasClase }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros (Opcional) -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-funnel"></i> Filtros
            </h5>
            <form method="GET" action="{{ route('profesor.asistencias.historial', $grupo->id) }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="mes" class="form-label">Mes</label>
                        <select name="mes" id="mes" class="form-select">
                            <option value="">Todos los meses</option>
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        // Calcular estadísticas generales
        $totalAlumnos = count($estadisticas);
        $promedioAsistencia = $totalAlumnos > 0 ? collect($estadisticas)->avg('porcentaje_asistencia') : 0;
        $mejorAlumno = $totalAlumnos > 0 ? collect($estadisticas)->sortByDesc('porcentaje_asistencia')->first() : null;
        $alumnosRiesgo = collect($estadisticas)->where('porcentaje_asistencia', '<', 80)->count();
        
        // Ordenar estadísticas por porcentaje descendente
        $estadisticasOrdenadas = collect($estadisticas)->sortByDesc('porcentaje_asistencia');
    @endphp

    <!-- Estadísticas Generales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Promedio del Grupo</h6>
                    <h2 class="mb-0 text-primary">{{ number_format($promedioAsistencia, 1) }}%</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Clases Impartidas</h6>
                    <h2 class="mb-0 text-success">{{ $fechasClase }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-info">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Mejor Asistencia</h6>
                    <h2 class="mb-0 text-info">
                        @if($mejorAlumno)
                            {{ number_format($mejorAlumno['porcentaje_asistencia'], 1) }}%
                        @else
                            N/A
                        @endif
                    </h2>
                    @if($mejorAlumno)
                        <small class="text-muted">{{ $mejorAlumno['alumno']->name }}</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Alumnos en Riesgo</h6>
                    <h2 class="mb-0 text-warning">{{ $alumnosRiesgo }}</h2>
                    <small class="text-muted">&lt; 80% asistencia</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Resumen por Alumno -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-table"></i> Resumen de Asistencias por Alumno
                </h5>
                <div>
                    <button class="btn btn-success btn-sm" onclick="exportToExcel()" disabled>
                        <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="tablaAsistencias">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th style="width: 120px;">Núm. Control</th>
                            <th>Nombre Completo</th>
                            <th class="text-center" style="width: 100px;">Total Clases</th>
                            <th class="text-center" style="width: 90px;">Presentes</th>
                            <th class="text-center" style="width: 90px;">Ausentes</th>
                            <th class="text-center" style="width: 90px;">Retardos</th>
                            <th class="text-center" style="width: 100px;">Justificados</th>
                            <th style="width: 200px;">% Asistencia</th>
                            <th class="text-center" style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estadisticasOrdenadas as $index => $stat)
                            @php
                                $alumno = $stat['alumno'];
                                $porcentaje = $stat['porcentaje_asistencia'];
                                
                                // Determinar color del progress bar
                                if ($porcentaje >= 90) {
                                    $colorProgress = 'bg-success';
                                } elseif ($porcentaje >= 80) {
                                    $colorProgress = 'bg-info';
                                } elseif ($porcentaje >= 70) {
                                    $colorProgress = 'bg-warning';
                                } else {
                                    $colorProgress = 'bg-danger';
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $alumno->numero_control ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <strong>{{ $alumno->name }}</strong>
                                    @if($alumno->carrera)
                                        <br><small class="text-muted">{{ $alumno->carrera->nombre }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark">{{ $stat['total_clases'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $stat['presentes'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $stat['ausentes'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">{{ $stat['retardos'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $stat['justificados'] }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 20px;">
                                            <div class="progress-bar {{ $colorProgress }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $porcentaje }}%;" 
                                                 aria-valuenow="{{ $porcentaje }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ number_format($porcentaje, 1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('profesor.asistencias.reporte-alumno', [$grupo->id, $alumno->id]) }}" 
                                       class="btn btn-sm btn-primary"
                                       title="Ver detalle">
                                        <i class="bi bi-eye"></i> Ver Detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2 mb-0">No hay registros de asistencia para este grupo.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
    .progress {
        background-color: #e9ecef;
    }
    
    .progress-bar {
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 20px;
    }
    
    .card {
        border-radius: 0.5rem;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }
    
    .btn-sm {
        font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Función para exportar a Excel (placeholder)
    function exportToExcel() {
        alert('Funcionalidad de exportación en desarrollo.');
        // Aquí se implementaría la exportación a Excel
        // Puede usar librerías como SheetJS o hacer una petición al backend
    }
    
    // Mantener los valores de los filtros después de filtrar
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('mes')) {
            document.getElementById('mes').value = urlParams.get('mes');
        }
        
        if (urlParams.has('fecha_inicio')) {
            document.getElementById('fecha_inicio').value = urlParams.get('fecha_inicio');
        }
        
        if (urlParams.has('fecha_fin')) {
            document.getElementById('fecha_fin').value = urlParams.get('fecha_fin');
        }
    });
</script>
@endpush
@endsection
