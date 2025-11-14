@extends('layouts.plantilla1')

@section('title', 'Mis Reservaciones de Equipos')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Mis Reservaciones</h2>
                    <p class="text-muted">Gestiona tus reservaciones de equipos</p>
                </div>
                <a href="{{ route('profesor.reservaciones.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Nueva Reservación
                </a>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history fs-1 text-warning"></i>
                    <h3 class="mt-2 mb-0">{{ $estadisticas['pendientes'] }}</h3>
                    <p class="text-muted mb-0">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <h3 class="mt-2 mb-0">{{ $estadisticas['aprobadas'] }}</h3>
                    <p class="text-muted mb-0">Aprobadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle fs-1 text-danger"></i>
                    <h3 class="mt-2 mb-0">{{ $estadisticas['rechazadas'] }}</h3>
                    <p class="text-muted mb-0">Rechazadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-list-check fs-1 text-info"></i>
                    <h3 class="mt-2 mb-0">{{ $estadisticas['total'] }}</h3>
                    <p class="text-muted mb-0">Total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('profesor.reservaciones.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                        <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha_desde" class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-3">
                    <label for="fecha_hasta" class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-3">
                    <label for="equipo_id" class="form-label">Equipo</label>
                    <select name="equipo_id" id="equipo_id" class="form-select">
                        <option value="">Todos los equipos</option>
                        @foreach($equipos as $equipo)
                            <option value="{{ $equipo->id }}" {{ request('equipo_id') == $equipo->id ? 'selected' : '' }}>
                                {{ $equipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('profesor.reservaciones.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de reservaciones -->
    <div class="card">
        <div class="card-body">
            @if($reservaciones->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Horario</th>
                                <th>Equipo</th>
                                <th>Aula</th>
                                <th>Grupo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservaciones as $reservacion)
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $reservacion->fecha_reservacion->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $reservacion->hora_inicio->format('H:i') }} - 
                                        {{ $reservacion->hora_fin->format('H:i') }}
                                    </td>
                                    <td>
                                        <i class="bi bi-pc-display me-1"></i>
                                        {{ $reservacion->equipo->nombre }}
                                        <br>
                                        <small class="text-muted">{{ $reservacion->equipo->codigo_inventario }}</small>
                                    </td>
                                    <td>
                                        <i class="bi bi-door-open me-1"></i>
                                        {{ $reservacion->aula->nombre }}
                                    </td>
                                    <td>
                                        @if($reservacion->grupo)
                                            {{ $reservacion->grupo->clave_grupo }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($reservacion->estado)
                                            @case('pendiente')
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock-history"></i> Pendiente
                                                </span>
                                                @break
                                            @case('aprobada')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Aprobada
                                                </span>
                                                @break
                                            @case('rechazada')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> Rechazada
                                                </span>
                                                @break
                                            @case('cancelada')
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-dash-circle"></i> Cancelada
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('profesor.reservaciones.show', $reservacion->id) }}" 
                                               class="btn btn-sm btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($reservacion->estado == 'pendiente')
                                                <a href="{{ route('profesor.reservaciones.edit', $reservacion->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            @if(in_array($reservacion->estado, ['pendiente', 'aprobada']) && $reservacion->fecha_reservacion >= now()->toDateString())
                                                <form action="{{ route('profesor.reservaciones.destroy', $reservacion->id) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('¿Estás seguro de cancelar esta reservación?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Cancelar">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-3">
                    {{ $reservaciones->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-3">No hay reservaciones registradas</p>
                    <a href="{{ route('profesor.reservaciones.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Crear primera reservación
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
