@extends('layouts.plantilla1')

@section('title', 'Reporte de Asistencias')

@section('contenido')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clipboard-data"></i> Reporte de Asistencias</h2>
    </div>

    {{-- Estadísticas Generales --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Asistencias</h6>
                    <h3>{{ number_format($estadisticas['total_asistencias']) }}</h3>
                    <small>{{ $estadisticas['porcentaje_asistencia'] }}% del total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Faltas</h6>
                    <h3>{{ number_format($estadisticas['total_faltas']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-title">Total Retardos</h6>
                    <h3>{{ number_format($estadisticas['total_retardos']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Porcentaje General</h6>
                    <h3>{{ $estadisticas['porcentaje_asistencia'] }}%</h3>
                    <small>Asistencia efectiva</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reportes.asistencias') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Carrera</label>
                    <select name="carrera_id" class="form-select">
                        <option value="">Todas las carreras</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->id }}" {{ $carreraId == $carrera->id ? 'selected' : '' }}>
                                {{ $carrera->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Grupo</label>
                    <select name="grupo_id" class="form-select">
                        <option value="">Todos los grupos</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ $grupoId == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->materia->nombre }} - {{ $grupo->clave_grupo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Asistencias --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Registro de Asistencias</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Alumno</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asistencias as $asistencia)
                        <tr>
                            <td>{{ $asistencia->fecha->format('d/m/Y') }}</td>
                            <td>{{ $asistencia->alumno->name }}</td>
                            <td>{{ $asistencia->sesionClase->asignacionAula->grupo->materia->nombre ?? 'N/A' }}</td>
                            <td>{{ $asistencia->sesionClase->asignacionAula->grupo->clave_grupo ?? 'N/A' }}</td>
                            <td>
                                @if($asistencia->estado == 'presente')
                                    <span class="badge bg-success">Presente</span>
                                @elseif($asistencia->estado == 'falta')
                                    <span class="badge bg-danger">Falta</span>
                                @elseif($asistencia->estado == 'retardo')
                                    <span class="badge bg-warning">Retardo</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($asistencia->estado) }}</span>
                                @endif
                            </td>
                            <td>{{ $asistencia->observaciones ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay registros de asistencia</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $asistencias->links() }}
        </div>
    </div>
</div>
@endsection
