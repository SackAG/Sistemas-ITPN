@extends('layouts.plantilla1')

@section('title', 'Editar Aula')

@section('contenido')
    <div class="container-fluid">
        {{-- Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.aulas.index') }}">Aulas</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Aula</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-pencil text-primary"></i>
                    Editar Aula
                </h2>
                <p class="text-muted mb-0">Actualiza la información del aula <strong>{{ $aula->nombre }}</strong></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-xl-6">
                {{-- Tarjeta del formulario --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-building me-2"></i>
                            Información del Aula
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('admin.aulas.update', $aula) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Nombre del Aula --}}
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-tag me-1"></i>
                                    Nombre del Aula <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $aula->nombre) }}"
                                       placeholder="Ej: A-101, Laboratorio 1, etc."
                                       maxlength="100"
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Nombre único que identifique el aula
                                </div>
                            </div>

                            {{-- Edificio --}}
                            <div class="mb-3">
                                <label for="edificio" class="form-label">
                                    <i class="bi bi-building me-1"></i>
                                    Edificio <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('edificio') is-invalid @enderror" 
                                       id="edificio" 
                                       name="edificio" 
                                       value="{{ old('edificio', $aula->edificio) }}"
                                       placeholder="Ej: Edificio A, Edificio Principal, etc."
                                       maxlength="100"
                                       required>
                                @error('edificio')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Ubicación o edificio donde se encuentra el aula
                                </div>
                            </div>

                            {{-- Capacidades --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="capacidad_alumnos" class="form-label">
                                            <i class="bi bi-people me-1"></i>
                                            Capacidad de Alumnos <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('capacidad_alumnos') is-invalid @enderror" 
                                               id="capacidad_alumnos" 
                                               name="capacidad_alumnos" 
                                               value="{{ old('capacidad_alumnos', $aula->capacidad_alumnos) }}"
                                               min="1"
                                               max="200"
                                               placeholder="30"
                                               required>
                                        @error('capacidad_alumnos')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="form-text">
                                            Máximo de alumnos (1-200)
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="capacidad_equipos" class="form-label">
                                            <i class="bi bi-pc-display me-1"></i>
                                            Capacidad de Equipos <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('capacidad_equipos') is-invalid @enderror" 
                                               id="capacidad_equipos" 
                                               name="capacidad_equipos" 
                                               value="{{ old('capacidad_equipos', $aula->capacidad_equipos) }}"
                                               min="0"
                                               max="200"
                                               placeholder="25"
                                               required>
                                        @error('capacidad_equipos')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="form-text">
                                            Máximo de equipos (0-200)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Estado Activo --}}
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="activo" value="0">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           role="switch" 
                                           id="activo" 
                                           name="activo"
                                           value="1"
                                           {{ old('activo', $aula->activo) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Aula activa
                                    </label>
                                    <div class="form-text">
                                        Las aulas inactivas no aparecerán disponibles para asignación
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Botones de acción --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.aulas.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>
                                    Actualizar Aula
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="card mt-4 border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Precauciones
                        </h6>
                        <ul class="mb-0 text-muted small">
                            <li>Si cambias el nombre, asegúrate de que no exista otro aula con el mismo</li>
                            <li>Si reduces la capacidad de equipos, verifica que no exceda los equipos actuales</li>
                            <li>Desactivar el aula ocultará su disponibilidad para nuevas asignaciones</li>
                            <li>Los cambios se aplicarán inmediatamente</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Columna lateral con información del aula --}}
            <div class="col-lg-4 col-xl-6">
                {{-- Estadísticas actuales --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Estadísticas Actuales
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                    <h3 class="text-primary mb-1">{{ $aula->equipos()->count() }}</h3>
                                    <small class="text-muted">Equipos Asignados</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                    <h3 class="text-success mb-1">{{ $aula->asignaciones()->count() }}</h3>
                                    <small class="text-muted">Asignaciones</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Uso de Equipos</small>
                            <small class="text-muted">
                                {{ $aula->equipos()->count() }} / {{ $aula->capacidad_equipos }}
                            </small>
                        </div>
                        @php
                            $porcentajeUso = $aula->capacidad_equipos > 0 
                                ? ($aula->equipos()->count() / $aula->capacidad_equipos * 100) 
                                : 0;
                            $progressClass = $porcentajeUso >= 90 ? 'danger' : ($porcentajeUso >= 70 ? 'warning' : 'success');
                        @endphp
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $progressClass }}" 
                                 role="progressbar" 
                                 style="width: {{ min($porcentajeUso, 100) }}%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información de registro --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Información de Registro
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <i class="bi bi-calendar-plus text-primary me-2"></i>
                                <strong>Creado:</strong> 
                                {{ $aula->created_at->format('d/m/Y H:i') }}
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-calendar-check text-success me-2"></i>
                                <strong>Actualizado:</strong> 
                                {{ $aula->updated_at->format('d/m/Y H:i') }}
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Estado del aula --}}
                <div class="card shadow-sm mt-4 border-{{ $aula->activo ? 'success' : 'secondary' }}">
                    <div class="card-body text-center">
                        @if($aula->activo)
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-success">Aula Activa</h5>
                            <p class="text-muted small mb-0">
                                Esta aula está disponible para asignaciones
                            </p>
                        @else
                            <i class="bi bi-x-circle-fill text-secondary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-secondary">Aula Inactiva</h5>
                            <p class="text-muted small mb-0">
                                Esta aula no está disponible para nuevas asignaciones
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus,
        .form-check-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #1a1d20;
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .card-header {
            border-bottom-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .bg-white {
            background-color: #1a1d20 !important;
        }
    </style>
@endsection
