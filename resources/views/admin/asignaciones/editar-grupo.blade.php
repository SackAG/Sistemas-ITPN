@extends('layouts.plantilla1')

@section('title', 'Editar Grupo de Asignaciones')

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
                            <a href="{{ route('admin.asignaciones.index') }}">Asignaciones de Aula</a>
                        </li>
                        <li class="breadcrumb-item active">Editar Grupo</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-pencil-square text-primary"></i>
                            Editar Grupo de Asignaciones
                        </h2>
                        <p class="text-muted mb-0">Modifica todas las asignaciones del mismo horario a la vez</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Información del grupo actual --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Información Actual
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Aula:</strong>
                        <p class="mb-0">{{ $asignaciones->first()->aula->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Grupo:</strong>
                        <p class="mb-0">{{ $asignaciones->first()->grupo->clave_grupo ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Materia:</strong>
                        <p class="mb-0">{{ $asignaciones->first()->grupo->materia->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Horario:</strong>
                        <p class="mb-0">
                            {{ substr($asignaciones->first()->hora_inicio, 0, 5) }} - 
                            {{ substr($asignaciones->first()->hora_fin, 0, 5) }}
                        </p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <strong>Días asignados:</strong>
                        <div class="mt-2">
                            @foreach($asignaciones as $asignacion)
                                <span class="badge bg-primary me-1">{{ $asignacion->dia_semana }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de edición --}}
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil me-2"></i>
                            Editar Datos del Grupo
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.asignaciones.actualizar-grupo') }}" method="POST" id="formActualizar">
                            @csrf
                            @method('PUT')

                            {{-- IDs de las asignaciones --}}
                            @foreach($asignaciones as $asignacion)
                                <input type="hidden" name="ids[]" value="{{ $asignacion->id }}">
                            @endforeach

                            <div class="row mb-3">
                                {{-- Aula --}}
                                <div class="col-md-6">
                                    <label for="aula_id" class="form-label">
                                        <i class="bi bi-door-open me-1"></i>
                                        Aula <span class="text-danger">*</span>
                                    </label>
                                    <select name="aula_id" id="aula_id" class="form-select @error('aula_id') is-invalid @enderror" required>
                                        <option value="">-- Selecciona un aula --</option>
                                        @foreach($aulas as $aula)
                                            <option value="{{ $aula->id }}" {{ old('aula_id', $asignaciones->first()->aula_id) == $aula->id ? 'selected' : '' }}>
                                                {{ $aula->nombre }} - Capacidad: {{ $aula->capacidad }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('aula_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Grupo --}}
                                <div class="col-md-6">
                                    <label for="grupo_id" class="form-label">
                                        <i class="bi bi-people me-1"></i>
                                        Grupo <span class="text-danger">*</span>
                                    </label>
                                    <select name="grupo_id" id="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror" required>
                                        <option value="">-- Selecciona un grupo --</option>
                                        @foreach($grupos as $grupo)
                                            <option value="{{ $grupo->id }}" {{ old('grupo_id', $asignaciones->first()->grupo_id) == $grupo->id ? 'selected' : '' }}>
                                                {{ $grupo->clave_grupo }} - {{ $grupo->materia->nombre ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grupo_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                {{-- Hora inicio --}}
                                <div class="col-md-6">
                                    <label for="hora_inicio" class="form-label">
                                        <i class="bi bi-clock me-1"></i>
                                        Hora de Inicio <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" 
                                           name="hora_inicio" 
                                           id="hora_inicio" 
                                           class="form-control @error('hora_inicio') is-invalid @enderror"
                                           value="{{ old('hora_inicio', substr($asignaciones->first()->hora_inicio, 0, 5)) }}"
                                           required>
                                    @error('hora_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Hora fin --}}
                                <div class="col-md-6">
                                    <label for="hora_fin" class="form-label">
                                        <i class="bi bi-clock-fill me-1"></i>
                                        Hora de Fin <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" 
                                           name="hora_fin" 
                                           id="hora_fin" 
                                           class="form-control @error('hora_fin') is-invalid @enderror"
                                           value="{{ old('hora_fin', substr($asignaciones->first()->hora_fin, 0, 5)) }}"
                                           required>
                                    @error('hora_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                {{-- Fecha inicio vigencia --}}
                                <div class="col-md-6">
                                    <label for="fecha_inicio_vigencia" class="form-label">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Fecha Inicio Vigencia <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="fecha_inicio_vigencia" 
                                           id="fecha_inicio_vigencia" 
                                           class="form-control @error('fecha_inicio_vigencia') is-invalid @enderror"
                                           value="{{ old('fecha_inicio_vigencia', $asignaciones->first()->fecha_inicio_vigencia?->format('Y-m-d')) }}"
                                           required>
                                    @error('fecha_inicio_vigencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Fecha fin vigencia --}}
                                <div class="col-md-6">
                                    <label for="fecha_fin_vigencia" class="form-label">
                                        <i class="bi bi-calendar-x me-1"></i>
                                        Fecha Fin Vigencia <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="fecha_fin_vigencia" 
                                           id="fecha_fin_vigencia" 
                                           class="form-control @error('fecha_fin_vigencia') is-invalid @enderror"
                                           value="{{ old('fecha_fin_vigencia', $asignaciones->first()->fecha_fin_vigencia?->format('Y-m-d')) }}"
                                           required>
                                    @error('fecha_fin_vigencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Estado activo --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" 
                                               name="activo" 
                                               id="activo" 
                                               class="form-check-input"
                                               value="1" 
                                               {{ old('activo', $asignaciones->first()->activo) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activo">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Asignaciones Activas
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Nota:</strong> Los cambios se aplicarán a todas las asignaciones del grupo ({{ $asignaciones->count() }} días).
                            </div>

                            {{-- Botones --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-2"></i>
                                            Actualizar Grupo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Panel lateral con acciones adicionales --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-trash me-2"></i>
                            Zona de Peligro
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Eliminar todas las asignaciones de este grupo de horario.
                        </p>
                        <form action="{{ route('admin.asignaciones.eliminar-grupo') }}" 
                              method="POST" 
                              id="formEliminar"
                              onsubmit="return confirm('¿Estás seguro de eliminar estas {{ $asignaciones->count() }} asignaciones? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')

                            @foreach($asignaciones as $asignacion)
                                <input type="hidden" name="ids[]" value="{{ $asignacion->id }}">
                            @endforeach

                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar Grupo Completo
                            </button>
                        </form>

                        <div class="alert alert-warning mt-3 mb-0">
                            <small>
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Las asignaciones con sesiones registradas no se eliminarán.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Días incluidos --}}
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-week me-2"></i>
                            Días Incluidos
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($asignaciones as $asignacion)
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>{{ $asignacion->dia_semana }}</strong>
                                    @if($asignacion->sesiones()->count() > 0)
                                        <span class="badge bg-info text-dark ms-2">
                                            {{ $asignacion->sesiones()->count() }} sesión(es)
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [data-bs-theme="dark"] .card {
            background-color: #1a1d20;
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .card-header {
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .card-header.bg-white {
            background-color: #1a1d20 !important;
        }
        
        [data-bs-theme="dark"] .card-header.bg-primary {
            background-color: #0d6efd !important;
        }
        
        [data-bs-theme="dark"] .card-header.bg-info {
            background-color: #0dcaf0 !important;
        }
        
        [data-bs-theme="dark"] .card-header.bg-danger {
            background-color: #dc3545 !important;
        }
        
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #2d3236;
            border-color: #495057;
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #2d3236;
            border-color: #86b7fe;
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .alert-info {
            background-color: #052c65;
            border-color: #084298;
            color: #9ec5fe;
        }
        
        [data-bs-theme="dark"] .alert-warning {
            background-color: #664d03;
            border-color: #997404;
            color: #ffecb5;
        }
    </style>
@endsection
