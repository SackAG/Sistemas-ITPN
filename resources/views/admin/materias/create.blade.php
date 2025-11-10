@extends('layouts.plantilla1')

@section('title', 'Crear Materia')

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
                            <a href="{{ route('admin.materias.index') }}">Materias</a>
                        </li>
                        <li class="breadcrumb-item active">Nueva Materia</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-plus-circle text-primary"></i>
                    Crear Nueva Materia
                </h2>
                <p class="text-muted mb-0">Completa el formulario para registrar una nueva materia</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Tarjeta del formulario --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-book me-2"></i>
                            Información de la Materia
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('admin.materias.store') }}" method="POST">
                            @csrf

                            {{-- Nombre --}}
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-tag me-1"></i>
                                    Nombre de la Materia <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej: Programación Orientada a Objetos"
                                       maxlength="255"
                                       required
                                       autofocus>
                                @error('nombre')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Clave --}}
                            <div class="mb-3">
                                <label for="clave" class="form-label">
                                    <i class="bi bi-key me-1"></i>
                                    Clave <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('clave') is-invalid @enderror" 
                                       id="clave" 
                                       name="clave" 
                                       value="{{ old('clave') }}"
                                       placeholder="Ej: POO-2024"
                                       maxlength="20"
                                       required>
                                @error('clave')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    La clave debe ser única (máximo 20 caracteres)
                                </div>
                            </div>

                            {{-- Carrera --}}
                            <div class="mb-3">
                                <label for="carrera_id" class="form-label">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    Carrera <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('carrera_id') is-invalid @enderror" 
                                        id="carrera_id" 
                                        name="carrera_id" 
                                        required>
                                    <option value="">Seleccionar carrera...</option>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('carrera_id')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Semestre --}}
                            <div class="mb-3">
                                <label for="semestre" class="form-label">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Semestre <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('semestre') is-invalid @enderror" 
                                       id="semestre" 
                                       name="semestre" 
                                       value="{{ old('semestre') }}"
                                       placeholder="Ej: 3"
                                       min="1"
                                       max="12"
                                       required>
                                @error('semestre')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Semestre del 1 al 12
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <div class="mb-4">
                                <label for="descripcion" class="form-label">
                                    <i class="bi bi-text-paragraph me-1"></i>
                                    Descripción <span class="text-muted">(Opcional)</span>
                                </label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="4"
                                          maxlength="500"
                                          placeholder="Descripción breve de la materia...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Máximo 500 caracteres
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Botones de acción --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.materias.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>
                                    Crear Materia
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Columna lateral --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-info">
                    <div class="card-body">
                        <h6 class="card-title text-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Información importante
                        </h6>
                        <ul class="mb-0 text-muted small">
                            <li class="mb-2">El nombre de la materia es obligatorio</li>
                            <li class="mb-2">La clave debe ser única en el sistema</li>
                            <li class="mb-2">La clave no puede tener más de 20 caracteres</li>
                            <li class="mb-2">La descripción es opcional (máximo 500 caracteres)</li>
                            <li>Podrás editar la información después</li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-lightbulb me-2"></i>
                            Ejemplos de Claves
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>POO-2024:</strong> Programación Orientada a Objetos
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>BD-101:</strong> Bases de Datos
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>CALC-1:</strong> Cálculo Diferencial
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>ING-SW:</strong> Ingeniería de Software
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm mt-4 border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Consejos
                        </h6>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2">
                                <i class="bi bi-arrow-right me-2"></i>
                                Usa claves cortas y descriptivas
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-arrow-right me-2"></i>
                                Incluye el año o semestre si aplica
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-arrow-right me-2"></i>
                                Evita caracteres especiales en la clave
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #1a1d20;
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .card-header {
            border-bottom-color: #2d3236;
        }
    </style>
@endsection
