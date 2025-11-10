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

                            {{-- Campos condicionales según rol --}}
                            <div id="rolDependentFields">
                                <div class="alert alert-info" id="fieldsAlert" style="display: none;">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <span id="alertText"></span>
                                </div>

                                <div class="row">
                                    {{-- Número de Control (solo alumno y profesor) --}}
                                    <div class="col-md-6" id="no-ctrl-field" style="display: none;">
                                        <div class="mb-3">
                                            <label for="no_ctrl" class="form-label">
                                                <i class="bi bi-card-text me-1"></i>
                                                Número de Control 
                                                <span class="text-danger" id="no_ctrl_required" style="display: none;">*</span>
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
                                            <div class="form-text" id="no_ctrl_help"></div>
                                        </div>
                                    </div>

                                    {{-- Carrera/Departamento (solo alumno y profesor) --}}
                                    <div class="col-md-6" id="carrera-field" style="display: none;">
                                        <div class="mb-3">
                                            <label for="carrera_id" class="form-label">
                                                <i class="bi bi-mortarboard me-1"></i>
                                                <span id="carrera_label_alumno" style="display: none;">Carrera</span>
                                                <span id="carrera_label_profesor" style="display: none;">Departamento/Carrera</span>
                                                <span class="text-danger" id="carrera_required" style="display: none;">*</span>
                                            </label>
                                            <select class="form-select @error('carrera_id') is-invalid @enderror" 
                                                    id="carrera_id" 
                                                    name="carrera_id">
                                                <option value="">Seleccionar...</option>
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
                                            <div class="form-text" id="carrera_help"></div>
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
                            <li class="mb-2"><strong>Profesor:</strong> Gestiona grupos y asistencias. Carrera opcional (representa departamento)</li>
                            <li class="mb-2"><strong>Alumno:</strong> Consulta horarios y asistencias. Requiere número de control y carrera</li>
                            <li class="mb-2">El email debe ser único en el sistema</li>
                            <li>La contraseña debe tener mínimo 8 caracteres</li>
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
            const rolValue = this.value;
            
            // Elementos del DOM
            const fieldsAlert = document.getElementById('fieldsAlert');
            const alertText = document.getElementById('alertText');
            const noCtrlField = document.getElementById('no-ctrl-field');
            const carreraField = document.getElementById('carrera-field');
            const noCtrlInput = document.getElementById('no_ctrl');
            const carreraInput = document.getElementById('carrera_id');
            const noCtrlRequired = document.getElementById('no_ctrl_required');
            const carreraRequired = document.getElementById('carrera_required');
            const noCtrlHelp = document.getElementById('no_ctrl_help');
            const carreraHelp = document.getElementById('carrera_help');
            const carreraLabelAlumno = document.getElementById('carrera_label_alumno');
            const carreraLabelProfesor = document.getElementById('carrera_label_profesor');
            
            if (rolValue === 'alumno') {
                // ALUMNO: ambos campos obligatorios
                fieldsAlert.style.display = 'block';
                alertText.textContent = 'Los siguientes campos son obligatorios para alumnos';
                
                // Mostrar campos
                noCtrlField.style.display = 'block';
                carreraField.style.display = 'block';
                
                // Configurar como obligatorios
                noCtrlInput.required = true;
                carreraInput.required = true;
                noCtrlRequired.style.display = 'inline';
                carreraRequired.style.display = 'inline';
                
                // Labels y textos de ayuda
                carreraLabelAlumno.style.display = 'inline';
                carreraLabelProfesor.style.display = 'none';
                noCtrlHelp.textContent = 'Obligatorio para alumnos';
                carreraHelp.textContent = 'Obligatorio para alumnos';
                
            } else if (rolValue === 'profesor') {
                // PROFESOR: ambos campos opcionales
                fieldsAlert.style.display = 'block';
                alertText.textContent = 'Los siguientes campos son opcionales para profesores';
                
                // Mostrar campos
                noCtrlField.style.display = 'block';
                carreraField.style.display = 'block';
                
                // Configurar como opcionales
                noCtrlInput.required = false;
                carreraInput.required = false;
                noCtrlRequired.style.display = 'none';
                carreraRequired.style.display = 'none';
                
                // Labels y textos de ayuda
                carreraLabelAlumno.style.display = 'none';
                carreraLabelProfesor.style.display = 'inline';
                noCtrlHelp.textContent = 'Opcional para profesores';
                carreraHelp.textContent = 'Opcional (representa su departamento)';
                
            } else {
                // ADMIN: ocultar todo
                fieldsAlert.style.display = 'none';
                noCtrlField.style.display = 'none';
                carreraField.style.display = 'none';
                
                // Quitar requerimientos
                noCtrlInput.required = false;
                carreraInput.required = false;
            }
        });

        // Trigger al cargar si hay valor old
        @if(old('rol'))
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
