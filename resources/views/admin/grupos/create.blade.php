@extends('layouts.plantilla1')

@section('title', 'Crear Grupo')

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
                            <a href="{{ route('admin.grupos.index') }}">Grupos</a>
                        </li>
                        <li class="breadcrumb-item active">Crear Grupo</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-plus-circle text-primary"></i>
                    Crear Nuevo Grupo
                </h2>
                <p class="text-muted mb-0">Completa el formulario para crear un nuevo grupo</p>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Información del Grupo
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.grupos.store') }}" method="POST">
                            @csrf

                            {{-- Materia --}}
                            <div class="mb-3">
                                <label for="materia_id" class="form-label">
                                    <i class="bi bi-book me-1"></i>
                                    Materia <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('materia_id') is-invalid @enderror" 
                                        id="materia_id" 
                                        name="materia_id" 
                                        required>
                                    <option value="">-- Selecciona una materia --</option>
                                    @foreach($materias as $materia)
                                        <option value="{{ $materia->id }}" 
                                                {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                            {{ $materia->nombre }} ({{ $materia->clave }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('materia_id')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Profesor --}}
                            <div class="mb-3">
                                <label for="profesor_id" class="form-label">
                                    <i class="bi bi-person-badge me-1"></i>
                                    Profesor <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('profesor_id') is-invalid @enderror" 
                                        id="profesor_id" 
                                        name="profesor_id" 
                                        required>
                                    <option value="">-- Selecciona un profesor --</option>
                                    @foreach($profesores as $profesor)
                                        <option value="{{ $profesor->id }}" 
                                                {{ old('profesor_id') == $profesor->id ? 'selected' : '' }}>
                                            {{ $profesor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('profesor_id')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Solo usuarios con rol de profesor pueden ser asignados
                                </div>
                            </div>

                            {{-- Clave del Grupo --}}
                            <div class="mb-3">
                                <label for="clave_grupo" class="form-label">
                                    <i class="bi bi-key me-1"></i>
                                    Clave del Grupo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('clave_grupo') is-invalid @enderror" 
                                       id="clave_grupo" 
                                       name="clave_grupo" 
                                       placeholder="Ej: A, B, 101, 5A"
                                       value="{{ old('clave_grupo') }}"
                                       maxlength="50"
                                       required>
                                @error('clave_grupo')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Identificador único del grupo (máximo 50 caracteres)
                                </div>
                            </div>

                            <div class="row">
                                {{-- Periodo --}}
                                <div class="col-md-6 mb-3">
                                    <label for="periodo" class="form-label">
                                        <i class="bi bi-calendar-range me-1"></i>
                                        Periodo <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('periodo') is-invalid @enderror" 
                                            id="periodo" 
                                            name="periodo" 
                                            required>
                                        <option value="Ene-Abr" 
                                                {{ (old('periodo') ?? $periodoActual) == 'Ene-Abr' ? 'selected' : '' }}>
                                            Ene-Abr
                                        </option>
                                        <option value="May-Ago" 
                                                {{ (old('periodo') ?? $periodoActual) == 'May-Ago' ? 'selected' : '' }}>
                                            May-Ago
                                        </option>
                                        <option value="Sep-Dic" 
                                                {{ (old('periodo') ?? $periodoActual) == 'Sep-Dic' ? 'selected' : '' }}>
                                            Sep-Dic
                                        </option>
                                    </select>
                                    @error('periodo')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Año --}}
                                <div class="col-md-6 mb-3">
                                    <label for="año" class="form-label">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Año <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('año') is-invalid @enderror" 
                                           id="año" 
                                           name="año" 
                                           placeholder="Ej: 2025"
                                           value="{{ old('año', $añoActual) }}"
                                           min="2020"
                                           max="2030"
                                           required>
                                    @error('año')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Estado Activo (Checkbox) --}}
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="activo" value="0">
                                    <input class="form-check-input @error('activo') is-invalid @enderror" 
                                           type="checkbox" 
                                           id="activo" 
                                           name="activo"
                                           value="1"
                                           {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Grupo activo
                                    </label>
                                </div>
                                @error('activo')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Los grupos inactivos no podrán recibir inscripciones de alumnos
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.grupos.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>
                                    Crear Grupo
                                </button>
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
