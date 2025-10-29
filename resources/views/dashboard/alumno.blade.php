@extends('layouts.plantilla1')

@section('title', 'Mi Panel - Alumno')

@section('contenido')
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-4">Mi Panel - Alumno</h2>
            </div>
        </div>

        {{-- Sección: Mis Grupos --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Mis Grupos Inscritos</h3>
                        
                        @if($grupos->count() > 0)
                            <div class="row g-3">
                                @foreach($grupos as $grupo)
                                    <div class="col-md-4">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $grupo->materia->nombre }}</h5>
                                                <p class="card-text mb-1">
                                                    <strong>Profesor:</strong> {{ $grupo->profesor->name }}
                                                </p>
                                                <p class="card-text mb-1">
                                                    <strong>Grupo:</strong> {{ $grupo->clave_grupo }}
                                                </p>
                                                <p class="card-text mb-1">
                                                    <strong>Periodo:</strong> {{ $grupo->periodo }}
                                                </p>
                                                <p class="card-text text-muted small mt-2">
                                                    Inscrito: {{ \Carbon\Carbon::parse($grupo->pivot->fecha_inscripcion)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No estás inscrito en ningún grupo actualmente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección: Acciones Rápidas --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Acciones Rápidas</h3>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="#" class="card text-center text-decoration-none border-primary">
                                    <div class="card-body">
                                        <i class="bi bi-calendar3 fs-1 text-primary mb-3"></i>
                                        <p class="fw-semibold mb-0">Ver mi Horario</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="#" class="card text-center text-decoration-none border-success">
                                    <div class="card-body">
                                        <i class="bi bi-pc-display fs-1 text-success mb-3"></i>
                                        <p class="fw-semibold mb-0">Reservar Equipo</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="#" class="card text-center text-decoration-none border-info">
                                    <div class="card-body">
                                        <i class="bi bi-check2-circle fs-1 text-info mb-3"></i>
                                        <p class="fw-semibold mb-0">Ver mis Asistencias</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection