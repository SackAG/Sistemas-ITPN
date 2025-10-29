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
                    <div class="card-body">
                        <h3 class="card-title mb-4">Mis Grupos</h3>
                        
                        @if($grupos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Materia</th>
                                            <th>Clave Grupo</th>
                                            <th>Periodo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grupos as $grupo)
                                            <tr>
                                                <td>{{ $grupo->materia->nombre }}</td>
                                                <td>{{ $grupo->clave_grupo }}</td>
                                                <td>{{ $grupo->periodo }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-primary">
                                                        Registrar Clase
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