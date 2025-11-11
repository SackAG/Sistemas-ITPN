@extends('layouts.plantilla1')

@section('title', 'Nueva Asignación de Aula')

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
                        <li class="breadcrumb-item active">Nueva Asignación</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-plus-circle text-primary"></i>
                            Nueva Asignación de Aula
                        </h2>
                        <p class="text-muted mb-0">Asigna un aula a un grupo en un horario específico</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-week me-2"></i>
                            Datos de la Asignación
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                            @csrf

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
                                            <option value="{{ $aula->id }}" {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
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
                                            <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                                {{ $grupo->clave_grupo }} - {{ $grupo->materia->nombre ?? 'N/A' }} - {{ $grupo->profesor->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grupo_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                {{-- Día de la semana --}}
                                <div class="col-md-4">
                                    <label for="dia_semana" class="form-label">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Día de la Semana <span class="text-danger">*</span>
                                    </label>
                                    <select name="dia_semana" id="dia_semana" class="form-select @error('dia_semana') is-invalid @enderror" required>
                                        <option value="">-- Selecciona un día --</option>
                                        @foreach($diasSemana as $dia)
                                            <option value="{{ $dia }}" {{ old('dia_semana') == $dia ? 'selected' : '' }}>
                                                {{ $dia }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dia_semana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Hora inicio --}}
                                <div class="col-md-4">
                                    <label for="hora_inicio" class="form-label">
                                        <i class="bi bi-clock me-1"></i>
                                        Hora de Inicio <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" 
                                           name="hora_inicio" 
                                           id="hora_inicio" 
                                           class="form-control @error('hora_inicio') is-invalid @enderror"
                                           value="{{ old('hora_inicio', '07:00') }}"
                                           required>
                                    @error('hora_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Hora fin --}}
                                <div class="col-md-4">
                                    <label for="hora_fin" class="form-label">
                                        <i class="bi bi-clock-fill me-1"></i>
                                        Hora de Fin <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" 
                                           name="hora_fin" 
                                           id="hora_fin" 
                                           class="form-control @error('hora_fin') is-invalid @enderror"
                                           value="{{ old('hora_fin', '09:00') }}"
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
                                           value="{{ old('fecha_inicio_vigencia', $fechaInicio) }}"
                                           required>
                                    @error('fecha_inicio_vigencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Inicio del periodo actual</small>
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
                                           value="{{ old('fecha_fin_vigencia', $fechaFin) }}"
                                           required>
                                    @error('fecha_fin_vigencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Fin del periodo actual</small>
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
                                               {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="activo">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Asignación Activa
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Marca esta opción para que la asignación esté activa desde su creación
                                    </small>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-2"></i>
                                            Guardar Asignación
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        
        [data-bs-theme="dark"] .card-header.bg-primary {
            background-color: #0d6efd !important;
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
    </style>
@endsection
