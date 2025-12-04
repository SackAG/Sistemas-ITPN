@extends('layouts.plantilla1')

@section('title', 'Reporte de Ocupación de Aulas')

@section('contenido')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pie-chart"></i> Reporte de Ocupación de Aulas</h2>
    </div>

    {{-- Estadísticas Generales --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Aulas</h6>
                    <h3>{{ number_format($estadisticas['total_aulas']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Edificios</h6>
                    <h3>{{ number_format($estadisticas['total_edificios']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Capacidad Alumnos</h6>
                    <h3>{{ number_format($estadisticas['capacidad_total_alumnos']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6 class="card-title">Capacidad Equipos</h6>
                    <h3>{{ number_format($estadisticas['capacidad_total_equipos']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reportes.aulas') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Edificio</label>
                    <select name="edificio" class="form-select">
                        <option value="">Todos los edificios</option>
                        @foreach($edificios as $ed)
                            <option value="{{ $ed }}" {{ $edificio == $ed ? 'selected' : '' }}>
                                Edificio {{ $ed }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Aula</label>
                    <select name="aula_id" class="form-select">
                        <option value="">Todas las aulas</option>
                        @foreach($aulas as $aula)
                            <option value="{{ $aula->id }}" {{ $aulaId == $aula->id ? 'selected' : '' }}>
                                {{ $aula->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Día de la Semana</label>
                    <select name="dia_semana" class="form-select">
                        <option value="">Todos los días</option>
                        <option value="lunes" {{ $diaSemana == 'lunes' ? 'selected' : '' }}>Lunes</option>
                        <option value="martes" {{ $diaSemana == 'martes' ? 'selected' : '' }}>Martes</option>
                        <option value="miercoles" {{ $diaSemana == 'miercoles' ? 'selected' : '' }}>Miércoles</option>
                        <option value="jueves" {{ $diaSemana == 'jueves' ? 'selected' : '' }}>Jueves</option>
                        <option value="viernes" {{ $diaSemana == 'viernes' ? 'selected' : '' }}>Viernes</option>
                        <option value="sabado" {{ $diaSemana == 'sabado' ? 'selected' : '' }}>Sábado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ocupación por Día --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Ocupación por Día de la Semana</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'] as $dia)
                <div class="col-md-2 mb-3">
                    <div class="text-center p-3 border rounded">
                        <h6 class="text-uppercase">{{ $dia }}</h6>
                        <h3 class="text-primary">{{ $ocupacionPorDia->get($dia)->total ?? 0 }}</h3>
                        <small class="text-muted">asignaciones</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Aulas Más Ocupadas --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Aulas Más Ocupadas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Edificio</th>
                            <th>Capacidad Alumnos</th>
                            <th>Capacidad Equipos</th>
                            <th>Total Asignaciones</th>
                            <th>Horas Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aulasMasOcupadas as $aula)
                        <tr>
                            <td><strong>{{ $aula->nombre }}</strong></td>
                            <td>{{ $aula->edificio }}</td>
                            <td>{{ $aula->capacidad_alumnos }}</td>
                            <td>{{ $aula->capacidad_equipos }}</td>
                            <td><span class="badge bg-primary">{{ $aula->total_asignaciones }}</span></td>
                            <td>{{ $horariosDisponibles[$aula->id] ?? 0 }} hrs/semana</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay datos disponibles</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Asignaciones Actuales --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Asignaciones de Aulas Vigentes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Edificio</th>
                            <th>Día</th>
                            <th>Horario</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Profesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignaciones as $asignacion)
                        <tr>
                            <td><strong>{{ $asignacion->aula->nombre }}</strong></td>
                            <td>{{ $asignacion->aula->edificio }}</td>
                            <td class="text-capitalize">{{ $asignacion->dia_semana }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($asignacion->hora_inicio)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($asignacion->hora_fin)->format('H:i') }}
                            </td>
                            <td>{{ $asignacion->grupo->materia->nombre }}</td>
                            <td>{{ $asignacion->grupo->clave_grupo }}</td>
                            <td>{{ $asignacion->grupo->profesor->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay asignaciones vigentes</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
