@extends('layouts.plantilla1')

@section('title', 'Detalle de Reservación')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('profesor.reservaciones.index') }}">Reservaciones</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-info-circle me-2"></i>Detalle de Reservación</h2>
                <div>
                    @if($reservacion->estado == 'pendiente')
                        <a href="{{ route('profesor.reservaciones.edit', $reservacion->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </a>
                    @endif
                    <a href="{{ route('profesor.reservaciones.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Información de la Reservación</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">FECHA</label>
                            <p class="fs-5 mb-0">
                                <i class="bi bi-calendar me-2"></i>
                                {{ $reservacion->fecha_reservacion->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $reservacion->fecha_reservacion->isoFormat('dddd') }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">HORARIO</label>
                            <p class="fs-5 mb-0">
                                <i class="bi bi-clock me-2"></i>
                                {{ $reservacion->hora_inicio->format('H:i') }} - {{ $reservacion->hora_fin->format('H:i') }}
                                <br>
                                <small class="text-muted">
                                    Duración: 
                                    {{ $reservacion->hora_inicio->diffInHours($reservacion->hora_fin) }} horas
                                    {{ $reservacion->hora_inicio->diffInMinutes($reservacion->hora_fin) % 60 }} min
                                </small>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">EQUIPO</label>
                            <p class="mb-0">
                                <i class="bi bi-pc-display me-2"></i>
                                {{ $reservacion->equipo->nombre }}
                                <br>
                                <small class="text-muted">
                                    <strong>Código:</strong> {{ $reservacion->equipo->codigo_inventario }}<br>
                                    <strong>Tipo:</strong> {{ ucfirst($reservacion->equipo->tipo) }}
                                </small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">AULA</label>
                            <p class="mb-0">
                                <i class="bi bi-door-open me-2"></i>
                                {{ $reservacion->aula->nombre }}
                                <br>
                                <small class="text-muted">
                                    Capacidad: {{ $reservacion->aula->capacidad }} personas
                                </small>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">GRUPO</label>
                            <p class="mb-0">
                                @if($reservacion->grupo)
                                    <i class="bi bi-people me-2"></i>
                                    {{ $reservacion->grupo->clave_grupo }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $reservacion->grupo->materia->nombre }}
                                    </small>
                                @else
                                    <span class="text-muted">Sin grupo asignado</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">PROFESOR</label>
                            <p class="mb-0">
                                <i class="bi bi-person me-2"></i>
                                {{ $reservacion->profesor->name }}
                                <br>
                                <small class="text-muted">{{ $reservacion->profesor->email }}</small>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-0">
                        <label class="fw-bold text-muted small">MOTIVO</label>
                        <p class="mb-0">{{ $reservacion->motivo }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado y Acciones -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Estado</h5>
                </div>
                <div class="card-body text-center">
                    @switch($reservacion->estado)
                        @case('pendiente')
                            <i class="bi bi-clock-history fs-1 text-warning"></i>
                            <h4 class="mt-3 text-warning">Pendiente</h4>
                            <p class="text-muted">En espera de aprobación</p>
                            @break
                        @case('aprobada')
                            <i class="bi bi-check-circle fs-1 text-success"></i>
                            <h4 class="mt-3 text-success">Aprobada</h4>
                            <p class="text-muted">Tu reservación ha sido aprobada</p>
                            @break
                        @case('rechazada')
                            <i class="bi bi-x-circle fs-1 text-danger"></i>
                            <h4 class="mt-3 text-danger">Rechazada</h4>
                            <p class="text-muted">La reservación fue rechazada</p>
                            @break
                        @case('cancelada')
                            <i class="bi bi-dash-circle fs-1 text-secondary"></i>
                            <h4 class="mt-3 text-secondary">Cancelada</h4>
                            <p class="text-muted">Reservación cancelada</p>
                            @break
                    @endswitch

                    @if(in_array($reservacion->estado, ['pendiente', 'aprobada']) && $reservacion->fecha_reservacion >= now()->toDateString())
                        <hr>
                        <form action="{{ route('profesor.reservaciones.destroy', $reservacion->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de cancelar esta reservación?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle me-1"></i>Cancelar Reservación
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Información Adicional</h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <i class="bi bi-calendar-plus me-1"></i>
                        <strong>Creada:</strong><br>
                        {{ $reservacion->created_at->format('d/m/Y H:i') }}
                    </small>
                    <hr class="my-2">
                    <small class="text-muted">
                        <i class="bi bi-pencil me-1"></i>
                        <strong>Última actualización:</strong><br>
                        {{ $reservacion->updated_at->format('d/m/Y H:i') }}
                    </small>

                    @if($reservacion->fecha_reservacion > now()->toDateString())
                        <hr class="my-2">
                        <div class="alert alert-info py-2 mb-0">
                            <small>
                                <i class="bi bi-info-circle me-1"></i>
                                Faltan {{ now()->diffInDays($reservacion->fecha_reservacion) }} días
                            </small>
                        </div>
                    @elseif($reservacion->fecha_reservacion == now()->toDateString())
                        <hr class="my-2">
                        <div class="alert alert-success py-2 mb-0">
                            <small>
                                <i class="bi bi-calendar-check me-1"></i>
                                ¡La reservación es hoy!
                            </small>
                        </div>
                    @else
                        <hr class="my-2">
                        <div class="alert alert-secondary py-2 mb-0">
                            <small>
                                <i class="bi bi-calendar-x me-1"></i>
                                Reservación pasada
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
