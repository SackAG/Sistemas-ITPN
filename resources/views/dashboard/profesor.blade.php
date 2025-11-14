@extends('layouts.plantilla1')

@section('title', 'Mi Panel - Profesor')

@section('contenido')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-4">Mi Panel - Profesor</h2>
            </div>
        </div>

        {{-- Sección: Mis Grupos --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="bi bi-people-fill"></i> Mis Grupos
                            </h3>
                            <a href="{{ route('profesor.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-grid-3x3"></i> Ver Todos
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        @if($grupos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Materia</th>
                                            <th>Clave Grupo</th>
                                            <th>Periodo</th>
                                            <th>Alumnos</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grupos as $grupo)
                                            <tr>
                                                <td>{{ $grupo->materia->nombre }}</td>
                                                <td><span class="badge bg-secondary">{{ $grupo->clave_grupo }}</span></td>
                                                <td>{{ $grupo->periodo }} {{ $grupo->año }}</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-people"></i> {{ $grupo->alumnos->count() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('profesor.asistencias.pasar-lista', $grupo->id) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Pasar lista de hoy">
                                                        <i class="bi bi-clipboard-check"></i> Pasar Lista
                                                    </a>
                                                    <a href="{{ route('profesor.asistencias.historial', $grupo->id) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Ver historial completo">
                                                        <i class="bi bi-clock-history"></i> Historial
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No tienes grupos asignados actualmente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección: Últimas Sesiones --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Últimas Sesiones</h3>
                        
                        @if($sesiones->count() > 0)
                            <div class="row g-3">
                                @foreach($sesiones as $sesion)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="card-title">{{ $sesion->asignacionAula->grupo->materia->nombre }}</h5>
                                                        <p class="card-text mb-1">
                                                            <strong>Tema:</strong> {{ $sesion->tema->nombre }}
                                                        </p>
                                                        <p class="card-text text-muted small">
                                                            Tipo: {{ ucfirst($sesion->tipo_actividad) }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <p class="mb-0 fw-semibold">
                                                            {{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}
                                                        </p>
                                                        <p class="text-muted small mb-0">
                                                            {{ substr($sesion->hora_inicio_real, 0, 5) }} - {{ substr($sesion->hora_fin_real, 0, 5) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No has registrado sesiones recientemente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection