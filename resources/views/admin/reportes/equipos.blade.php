@extends('layouts.plantilla1')

@section('title', 'Reporte de Uso de Equipos')

@section('contenido')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bar-chart"></i> Reporte de Uso de Equipos</h2>
    </div>

    {{-- Estadísticas Generales --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Equipos</h6>
                    <h3>{{ number_format($estadisticas['total_equipos']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Operativos</h6>
                    <h3>{{ number_format($estadisticas['equipos_operativos']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-title">Mantenimiento</h6>
                    <h3>{{ number_format($estadisticas['equipos_mantenimiento']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Dañados</h6>
                    <h3>{{ number_format($estadisticas['equipos_dañados']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="card-title">Dados de Baja</h6>
                    <h3>{{ number_format($estadisticas['equipos_baja']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reportes.equipos') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Aula</label>
                    <select name="aula_id" class="form-select">
                        <option value="">Todas las aulas</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}" {{ $aulaId == $aula->id ? 'selected' : '' }}>
                                {{ $aula->nombre }} ({{ $aula->edificio }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                </div>
                <div class="col-md-3">
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

    {{-- Equipos Más Usados --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Equipos Más Usados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Aula</th>
                            <th>Estado</th>
                            <th>Total Usos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equiposMasUsados as $equipo)
                        <tr>
                            <td><code>{{ $equipo->codigo_inventario ?? 'N/A' }}</code></td>
                            <td>{{ ucfirst($equipo->tipo ?? 'N/A') }}</td>
                            <td>{{ optional($equipo->aula)->nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $equipo->estado == 'operativo' ? 'success' : 'warning' }}">
                                    {{ ucfirst($equipo->estado) }}
                                </span>
                            </td>
                            <td><strong>{{ $equipo->total_usos }}</strong></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay datos disponibles</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Uso Libre de Equipos --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Uso Libre de Equipos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Alumno</th>
                            <th>Equipo</th>
                            <th>Aula</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usosLibres as $uso)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($uso->fecha_uso)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($uso->hora_inicio)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($uso->hora_fin)->format('H:i') }}</td>
                            <td>{{ $uso->alumno->name ?? 'N/A' }}</td>
                            <td><code>{{ $uso->equipo->codigo_inventario ?? 'N/A' }}</code></td>
                            <td>{{ $uso->equipo->aula->nombre ?? 'N/A' }}</td>
                            <td>{{ $uso->observaciones ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay registros de uso libre</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $usosLibres->links() }}
        </div>
    </div>

    {{-- Reservaciones de Equipos --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Reservaciones de Equipos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Alumno</th>
                            <th>Equipo</th>
                            <th>Aula</th>
                            <th>Materia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservaciones as $reservacion)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($reservacion->fecha_reservacion)->format('d/m/Y') }}</td>
                            <td>{{ $reservacion->alumno->name ?? $reservacion->profesor->name ?? 'N/A' }}</td>
                            <td><code>{{ $reservacion->equipo->codigo_inventario ?? 'N/A' }}</code></td>
                            <td>{{ $reservacion->equipo->aula->nombre ?? 'N/A' }}</td>
                            <td>{{ optional($reservacion->grupo)->materia->nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $reservacion->estado == 'aprobada' ? 'success' : ($reservacion->estado == 'pendiente' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($reservacion->estado) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay reservaciones registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reservaciones->links() }}
        </div>
    </div>
</div>
@endsection
