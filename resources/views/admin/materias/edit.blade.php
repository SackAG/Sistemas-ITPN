@extends('layouts.plantilla1')

@section('title', 'Editar Materia')

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
                        <li class="breadcrumb-item active">Editar Materia</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-pencil text-primary"></i>
                    Editar Materia
                </h2>
                <p class="text-muted mb-0">Actualiza la información de la materia</p>
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
                        <form action="{{ route('admin.materias.update', $materia) }}" method="POST">
                            @csrf
                            @method('PUT')

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
                                       value="{{ old('nombre', $materia->nombre) }}"
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
                                       value="{{ old('clave', $materia->clave) }}"
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
                                        <option value="{{ $carrera->id }}" {{ old('carrera_id', $materia->carrera_id) == $carrera->id ? 'selected' : '' }}>
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
                                       value="{{ old('semestre', $materia->semestre) }}"
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
                                          placeholder="Descripción breve de la materia...">{{ old('descripcion', $materia->descripcion) }}</textarea>
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
                                    Actualizar Materia
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
                            Información de la Materia
                        </h6>
                        <ul class="list-unstyled mb-0 text-muted small">
                            <li class="mb-2">
                                <strong>ID:</strong> 
                                <span class="badge bg-secondary">{{ $materia->id }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Grupos asociados:</strong> 
                                <span class="badge bg-primary">{{ $materia->grupos()->count() }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Creada:</strong> 
                                {{ $materia->created_at->format('d/m/Y H:i') }}
                            </li>
                            <li>
                                <strong>Última actualización:</strong> 
                                {{ $materia->updated_at->format('d/m/Y H:i') }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm mt-4 border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Advertencia
                        </h6>
                        <p class="small text-muted mb-0">
                            @if($materia->grupos()->count() > 0)
                                Esta materia tiene <strong>{{ $materia->grupos()->count() }}</strong> 
                                grupo(s) asociado(s). Al cambiar el nombre o clave, se actualizará para todos los grupos.
                            @else
                                Esta materia no tiene grupos asociados.
                            @endif
                        </p>
                    </div>
                </div>

                @if($materia->grupos()->count() === 0)
                    <div class="card shadow-sm mt-4 border-danger">
                        <div class="card-body">
                            <h6 class="card-title text-danger">
                                <i class="bi bi-trash me-2"></i>
                                Zona Peligrosa
                            </h6>
                            <p class="small text-muted mb-3">
                                Puedes eliminar esta materia ya que no tiene grupos asociados.
                            </p>
                            <button type="button" 
                                    class="btn btn-danger btn-sm w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar Materia
                            </button>
                        </div>
                    </div>

                    {{-- Modal de confirmación --}}
                    <div class="modal fade" id="deleteModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                        Confirmar Eliminación
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que deseas eliminar la materia <strong>{{ $materia->nombre }}</strong> ({{ $materia->clave }})?</p>
                                    <p class="text-danger mb-0">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Esta acción no se puede deshacer.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <form action="{{ route('admin.materias.destroy', $materia) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash me-2"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
