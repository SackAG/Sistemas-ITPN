@extends('layouts.plantilla1')

@section('title', 'Crear Usuario')

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
                            <a href="{{ route('admin.users.index') }}">Usuarios</a>
                        </li>
                        <li class="breadcrumb-item active">Nuevo Usuario</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-person-plus text-primary"></i>
                    Crear Nuevo Usuario
                </h2>
                <p class="text-muted mb-0">Completa el formulario para registrar un nuevo usuario</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Tarjeta del formulario --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge me-2"></i>
                            Información del Usuario
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
                            @csrf

                            {{-- Nombre completo --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Ej: Juan Pérez López"
                                       maxlength="255"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>
                                    Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="ejemplo@tecnm.mx"
                                       maxlength="255"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Contraseña --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-key me-1"></i>
                                            Contraseña <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password"
                                               minlength="8"
                                               required>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="form-text">
                                            Mínimo 8 caracteres
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="bi bi-key-fill me-1"></i>
                                            Confirmar Contraseña <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               minlength="8"
                                               required>
                                        <div class="form-text">
                                            Repite la contraseña
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Rol --}}
                            <div class="mb-3">
                                <label for="rol" class="form-label">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Rol <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('rol') is-invalid @enderror" 
                                        id="rol" 
                                        name="rol"
                                        required>
                                    <option value="">Seleccionar rol...</option>
                                    <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>
                                        Administrador
                                    </option>
                                    <option value="profesor" {{ old('rol') === 'profesor' ? 'selected' : '' }}>
                                        Profesor
                                    </option>
                                    <option value="alumno" {{ old('rol') === 'alumno' ? 'selected' : '' }}>
                                        Alumno
                                    </option>
                                </select>
                                @error('rol')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Define el nivel de acceso del usuario
                                </div>
                            </div>

                            {{-- Campos para alumnos --}}
                            <div id="alumnoFields" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Los siguientes campos son obligatorios para alumnos
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="no_ctrl" class="form-label">
                                                <i class="bi bi-card-text me-1"></i>
                                                Número de Control <span class="text-danger" id="no_ctrl_required">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('no_ctrl') is-invalid @enderror" 
                                                   id="no_ctrl" 
                                                   name="no_ctrl" 
                                                   value="{{ old('no_ctrl') }}"
                                                   placeholder="Ej: 20210123"
                                                   maxlength="20">
                                            @error('no_ctrl')
                                                <div class="invalid-feedback">
                                                    <i class="bi bi-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="carrera_id" class="form-label">
                                                <i class="bi bi-mortarboard me-1"></i>
                                                Carrera <span class="text-danger" id="carrera_required">*</span>
                                            </label>
                                            <select class="form-select @error('carrera_id') is-invalid @enderror" 
                                                    id="carrera_id" 
                                                    name="carrera_id">
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
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Botones de acción --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>
                                    Crear Usuario
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
                            <li class="mb-2"><strong>Administrador:</strong> Acceso total al sistema</li>
                            <li class="mb-2"><strong>Profesor:</strong> Gestiona grupos y asistencias</li>
                            <li class="mb-2"><strong>Alumno:</strong> Consulta horarios y asistencias</li>
                            <li class="mb-2">El email debe ser único en el sistema</li>
                            <li class="mb-2">La contraseña debe tener mínimo 8 caracteres</li>
                            <li>Alumnos requieren número de control y carrera</li>
                        </ul>
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
                                Usa contraseñas seguras para administradores
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Verifica el email antes de crear el usuario
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Puedes resetear contraseñas más tarde
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar/ocultar campos según el rol seleccionado
        document.getElementById('rol').addEventListener('change', function() {
            const alumnoFields = document.getElementById('alumnoFields');
            const noCtrlInput = document.getElementById('no_ctrl');
            const carreraInput = document.getElementById('carrera_id');
            
            if (this.value === 'alumno') {
                alumnoFields.style.display = 'block';
                noCtrlInput.required = true;
                carreraInput.required = true;
            } else {
                alumnoFields.style.display = 'none';
                noCtrlInput.required = false;
                carreraInput.required = false;
            }
        });

        // Trigger al cargar si hay valor old
        @if(old('rol') === 'alumno')
            document.getElementById('rol').dispatchEvent(new Event('change'));
        @endif
    </script>

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
