@extends('layouts.plantilla1')

@section('title', 'Editar Carrera')

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
                        <li class="breadcrumb-item active">Editar Carrera</li>
                    </ol>
                </nav>
                <h2 class="mb-1">
                    <i class="bi bi-pencil text-primary"></i>
                    Editar Carrera
                </h2>
                <p class="text-muted mb-0">Actualiza la información de la carrera</p>
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
                        <form action="{{ route('admin.carreras.update', $carrera) }}" method="POST">
                            @csrf
                            @method('PUT')

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
                                       value="{{ old('nombre', $carrera->nombre) }}"
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
                                       value="{{ old('clave', $carrera->clave) }}"
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
                                    Actualizar Carrera
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
                            Información de la Carrera
                        </h6>
                        <ul class="list-unstyled mb-0 text-muted small">
                            <li class="mb-2">
                                <strong>ID:</strong> 
                                <span class="badge bg-secondary">{{ $carrera->id }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Usuarios asignados:</strong> 
                                <span class="badge bg-primary">{{ $carrera->usuarios()->count() }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Creada:</strong> 
                                {{ $carrera->created_at->format('d/m/Y H:i') }}
                            </li>
                            <li>
                                <strong>Última actualización:</strong> 
                                {{ $carrera->updated_at->format('d/m/Y H:i') }}
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
                            @if($carrera->usuarios()->count() > 0)
                                Esta carrera tiene <strong>{{ $carrera->usuarios()->count() }}</strong> 
                                usuario(s) asignado(s). Al cambiar el nombre, se actualizará para todos los usuarios.
                            @else
                                Esta carrera no tiene usuarios asignados.
                            @endif
                        </p>
                    </div>
                </div>

                @if($carrera->usuarios()->count() === 0)
                    <div class="card shadow-sm mt-4 border-danger">
                        <div class="card-body">
                            <h6 class="card-title text-danger">
                                <i class="bi bi-trash me-2"></i>
                                Zona Peligrosa
                            </h6>
                            <p class="small text-muted mb-3">
                                Puedes eliminar esta carrera ya que no tiene usuarios asignados.
                            </p>
                            <button type="button" 
                                    class="btn btn-danger btn-sm w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-2"></i>
                                Eliminar Carrera
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
                                    <p>¿Estás seguro de que deseas eliminar la carrera <strong>{{ $carrera->nombre }}</strong>?</p>
                                    <p class="text-danger mb-0">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Esta acción no se puede deshacer.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <form action="{{ route('admin.carreras.destroy', $carrera) }}" method="POST" style="display: inline;">
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
