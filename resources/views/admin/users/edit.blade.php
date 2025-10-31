@extends('layouts.plantilla1')

@section('title', 'Editar Usuario')

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
                        <li class="breadcrumb-item active">Editar Usuario</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-pencil text-primary"></i>
                    Editar Usuario
                </h2>
                <p class="text-muted mb-0">Actualiza la información de <strong>{{ $user->name }}</strong></p>
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
                        <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
                            @csrf
                            @method('PUT')

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
                                       value="{{ old('name', $user->name) }}"
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
                                       value="{{ old('email', $user->email) }}"
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

                            {{-- Contraseña (opcional) --}}
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Deja los campos de contraseña vacíos si no deseas cambiarla
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-key me-1"></i>
                                            Nueva Contraseña (opcional)
                                        </label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password"
                                               minlength="8">
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
                                            Confirmar Contraseña
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               minlength="8">
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
                                        required
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="">Seleccionar rol...</option>
                                    <option value="admin" {{ old('rol', $user->rol) === 'admin' ? 'selected' : '' }}>
                                        Administrador
                                    </option>
                                    <option value="profesor" {{ old('rol', $user->rol) === 'profesor' ? 'selected' : '' }}>
                                        Profesor
                                    </option>
                                    <option value="alumno" {{ old('rol', $user->rol) === 'alumno' ? 'selected' : '' }}>
                                        Alumno
                                    </option>
                                </select>
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="rol" value="{{ $user->rol }}">
                                    <div class="form-text text-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        No puedes cambiar tu propio rol
                                    </div>
                                @endif
                                @error('rol')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Campos para alumnos --}}
                            <div id="alumnoFields" style="display: {{ old('rol', $user->rol) === 'alumno' ? 'block' : 'none' }};">
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
                                                   value="{{ old('no_ctrl', $user->no_ctrl) }}"
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
                                                    <option value="{{ $carrera->id }}" {{ old('carrera_id', $user->carrera_id) == $carrera->id ? 'selected' : '' }}>
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
                                    Actualizar Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Columna lateral --}}
            <div class="col-lg-4">
                {{-- Estadísticas del usuario --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Información del Usuario
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <i class="bi bi-calendar-plus text-primary me-2"></i>
                                <strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-calendar-check text-success me-2"></i>
                                <strong>Última actualización:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                            </li>
                            @if($user->rol === 'profesor')
                                <li class="mb-2">
                                    <i class="bi bi-people-fill text-info me-2"></i>
                                    <strong>Grupos asignados:</strong> {{ $user->gruposComoProfesor()->count() }}
                                </li>
                            @elseif($user->rol === 'alumno')
                                <li class="mb-2">
                                    <i class="bi bi-people-fill text-success me-2"></i>
                                    <strong>Grupos inscritos:</strong> {{ $user->grupos()->count() }}
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-clipboard-check text-warning me-2"></i>
                                    <strong>Asistencias:</strong> {{ $user->asistencias()->count() }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Advertencias --}}
                <div class="card shadow-sm border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Precauciones
                        </h6>
                        <ul class="mb-0 text-muted small">
                            @if($user->id === auth()->id())
                                <li class="mb-2">No puedes cambiar tu propio rol</li>
                            @endif
                            <li class="mb-2">El email debe ser único en el sistema</li>
                            <li class="mb-2">Solo actualiza la contraseña si es necesario</li>
                            @if($user->rol === 'profesor' && $user->gruposComoProfesor()->count() > 0)
                                <li class="mb-2 text-danger">Este profesor tiene grupos asignados</li>
                            @endif
                            @if($user->rol === 'alumno' && $user->grupos()->count() > 0)
                                <li class="text-danger">Este alumno está inscrito en grupos</li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Badge del rol actual --}}
                <div class="card shadow-sm mt-4 border-{{ $user->rol === 'admin' ? 'danger' : ($user->rol === 'profesor' ? 'info' : 'success') }}">
                    <div class="card-body text-center">
                        @if($user->rol === 'admin')
                            <i class="bi bi-shield-check text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-danger">Administrador</h5>
                        @elseif($user->rol === 'profesor')
                            <i class="bi bi-person-workspace text-info" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-info">Profesor</h5>
                        @else
                            <i class="bi bi-person-video3 text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-success">Alumno</h5>
                        @endif
                        <p class="text-muted small mb-0">Rol actual del usuario</p>
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

        // Trigger al cargar si hay valor old o actual
        @if(old('rol', $user->rol) === 'alumno')
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
        
        [data-bs-theme="dark"] .bg-white {
            background-color: #1a1d20 !important;
        }
    </style>
@endsection
