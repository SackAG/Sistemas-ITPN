@extends('layouts.plantilla1')

@section('title', 'Crear Carrera')

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
                            <a href="{{ route('admin.carreras.index') }}">Carreras</a>
                        </li>
                        <li class="breadcrumb-item active">Nueva Carrera</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-plus-circle text-primary"></i>
                    Crear Nueva Carrera
                </h2>
                <p class="text-muted mb-0">Completa el formulario para registrar una nueva carrera</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Tarjeta del formulario --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-mortarboard me-2"></i>
                            Información de la Carrera
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('admin.carreras.store') }}" method="POST">
                            @csrf

                            {{-- Nombre --}}
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-tag me-1"></i>
                                    Nombre de la Carrera <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej: Ingeniería en Sistemas Computacionales"
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
                            <div class="mb-4">
                                <label for="clave" class="form-label">
                                    <i class="bi bi-key me-1"></i>
                                    Clave <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('clave') is-invalid @enderror" 
                                       id="clave" 
                                       name="clave" 
                                       value="{{ old('clave') }}"
                                       placeholder="Ej: ISC"
                                       maxlength="20"
                                       required>
                                @error('clave')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Clave única para identificar la carrera (máximo 20 caracteres)
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Botones de acción --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.carreras.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>
                                    Crear Carrera
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
                            <li class="mb-2">El nombre de la carrera es obligatorio</li>
                            <li class="mb-2">La clave debe ser única en el sistema</li>
                            <li class="mb-2">La clave es una abreviatura corta (máximo 20 caracteres)</li>
                            <li>Podrás editar la información después</li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-lightbulb me-2"></i>
                            Ejemplos
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>ISC:</strong> Ingeniería en Sistemas Computacionales
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>LA:</strong> Licenciatura en Administración
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>II:</strong> Ingeniería Industrial
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <strong>CP:</strong> Contador Público
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
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
