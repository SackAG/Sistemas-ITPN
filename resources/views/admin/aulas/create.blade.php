@extends('layouts.plantilla1')

@section('title', 'Crear Aula')

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
                        <li class="breadcrumb-item active">Nueva Aula</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-plus-circle text-primary"></i>
                    Crear Nueva Aula
                </h2>
                <p class="text-muted mb-0">Completa el formulario para registrar una nueva aula</p>
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
                        <form action="{{ route('admin.aulas.store') }}" method="POST">
                            @csrf

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
                                       value="{{ old('nombre') }}"
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
                                       value="{{ old('edificio') }}"
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
                                               value="{{ old('capacidad_alumnos') }}"
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
                                               value="{{ old('capacidad_equipos') }}"
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
                                           {{ old('activo', true) ? 'checked' : '' }}>
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
                                    Guardar Aula
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="card mt-4 border-info">
                    <div class="card-body">
                        <h6 class="card-title text-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Información importante
                        </h6>
                        <ul class="mb-0 text-muted small">
                            <li>El nombre del aula debe ser único en el sistema</li>
                            <li>La capacidad de alumnos debe ser entre 1 y 200</li>
                            <li>La capacidad de equipos puede ser de 0 a 200</li>
                            <li>Las aulas inactivas no podrán ser asignadas a grupos</li>
                            <li>Todos los campos marcados con <span class="text-danger">*</span> son obligatorios</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Columna lateral con vista previa --}}
            <div class="col-lg-4 col-xl-6">
                <div class="card shadow-sm bg-light border-0">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-building" style="font-size: 5rem; color: #0d6efd; opacity: 0.2;"></i>
                        <h5 class="mt-3 text-muted">Vista Previa</h5>
                        <p class="text-muted small mb-0">
                            Una vez creada, el aula estará disponible para asignar equipos y horarios
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-lightbulb me-2"></i>
                            Consejos
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Usa nombres descriptivos y fáciles de recordar
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Considera el espacio real del aula al definir capacidades
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Puedes editar la información más adelante
                            </li>
                        </ul>
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
        
        [data-bs-theme="dark"] .bg-light {
            background-color: #2d3236 !important;
        }
    </style>

    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const materiaSelect = document.getElementById('materia_id');
    const profesorSelect = document.getElementById('profesor_id');
    
    const materias = @json($materias->map(function($m) {
        return ['id' => $m->id, 'carrera_id' => $m->carrera_id];
    }));
    
    const profesores = @json($profesores->map(function($p) {
        return ['id' => $p->id, 'name' => $p->name, 'carrera_id' => $p->carrera_id];
    }));
    
    function filtrarProfesores() {
        const materiaId = materiaSelect.value;
        
        if (!materiaId) {
            profesorSelect.disabled = true;
            profesorSelect.innerHTML = '<option value="">Primero seleccione una materia...</option>';
            return;
        }
        
        const materia = materias.find(m => m.id == materiaId);
        const carreraId = materia ? materia.carrera_id : null;
        
        const profesoresFiltrados = carreraId 
            ? profesores.filter(p => p.carrera_id == carreraId)
            : profesores;
        
        profesorSelect.disabled = false;
        profesorSelect.innerHTML = '<option value="">Seleccionar profesor...</option>';
        
        if (profesoresFiltrados.length === 0) {
            profesorSelect.innerHTML = '<option value="">No hay profesores de esta carrera</option>';
            profesorSelect.disabled = true;
        } else {
            profesoresFiltrados.forEach(profesor => {
                const option = document.createElement('option');
                option.value = profesor.id;
                option.textContent = profesor.name;
                profesorSelect.appendChild(option);
            });
        }
    }
    
    if (materiaSelect.value) {
        filtrarProfesores();
    } else {
        profesorSelect.disabled = true;
        profesorSelect.innerHTML = '<option value="">Primero seleccione una materia...</option>';
    }
    
    materiaSelect.addEventListener('change', filtrarProfesores);
});
</script>
@endpush
@endsection
