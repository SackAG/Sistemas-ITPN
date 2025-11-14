@extends('layouts.plantilla1')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-pencil text-warning"></i> Editar Equipo
            </h1>
            <p class="text-muted mb-0">{{ $equipo->nombre ?? $equipo->codigo_inventario }}</p>
        </div>
        <div>
            <a href="{{ route('admin.equipos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>¡Errores en el formulario!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Información adicional -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">Fecha de Creación:</small>
                    <p class="mb-0 fw-semibold">{{ $equipo->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Última Actualización:</small>
                    <p class="mb-0 fw-semibold">{{ $equipo->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Uso en Últimos 30 Días:</small>
                    <p class="mb-0 fw-semibold">{{ $historialUso }} veces</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.equipos.update', $equipo->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i> Información General
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Equipo <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $equipo->nombre) }}"
                                   placeholder="Ej: Computadora HP 01"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo') is-invalid @enderror" 
                                        id="tipo" 
                                        name="tipo" 
                                        required>
                                    <option value="">Seleccione...</option>
                                    <option value="computadora" {{ old('tipo', $equipo->tipo) == 'computadora' ? 'selected' : '' }}>Computadora</option>
                                    <option value="proyector" {{ old('tipo', $equipo->tipo) == 'proyector' ? 'selected' : '' }}>Proyector</option>
                                    <option value="switch" {{ old('tipo', $equipo->tipo) == 'switch' ? 'selected' : '' }}>Switch</option>
                                    <option value="router" {{ old('tipo', $equipo->tipo) == 'router' ? 'selected' : '' }}>Router</option>
                                    <option value="impresora" {{ old('tipo', $equipo->tipo) == 'impresora' ? 'selected' : '' }}>Impresora</option>
                                    <option value="otro" {{ old('tipo', $equipo->tipo) == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('estado') is-invalid @enderror" 
                                        id="estado" 
                                        name="estado" 
                                        required>
                                    <option value="disponible" {{ old('estado', $equipo->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="en_uso" {{ old('estado', $equipo->estado) == 'en_uso' ? 'selected' : '' }}>En Uso</option>
                                    <option value="mantenimiento" {{ old('estado', $equipo->estado) == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="dañado" {{ old('estado', $equipo->estado) == 'dañado' ? 'selected' : '' }}>Dañado</option>
                                    <option value="dado_de_baja" {{ old('estado', $equipo->estado) == 'dado_de_baja' ? 'selected' : '' }}>Dado de Baja</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" 
                                       class="form-control @error('marca') is-invalid @enderror" 
                                       id="marca" 
                                       name="marca" 
                                       value="{{ old('marca', $equipo->marca) }}"
                                       placeholder="Ej: HP, Dell, Lenovo">
                                @error('marca')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" 
                                       class="form-control @error('modelo') is-invalid @enderror" 
                                       id="modelo" 
                                       name="modelo" 
                                       value="{{ old('modelo', $equipo->modelo) }}"
                                       placeholder="Ej: OptiPlex 7080">
                                @error('modelo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="numero_serie" class="form-label">Número de Serie <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('numero_serie') is-invalid @enderror" 
                                   id="numero_serie" 
                                   name="numero_serie" 
                                   value="{{ old('numero_serie', $equipo->numero_serie) }}"
                                   placeholder="Ej: SN1234567890"
                                   required>
                            @error('numero_serie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Debe ser único en el sistema</small>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición</label>
                            <input type="date" 
                                   class="form-control @error('fecha_adquisicion') is-invalid @enderror" 
                                   id="fecha_adquisicion" 
                                   name="fecha_adquisicion" 
                                   value="{{ old('fecha_adquisicion', $equipo->fecha_adquisicion?->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('fecha_adquisicion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-geo-alt"></i> Ubicación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="aula_id" class="form-label">Aula Asignada</label>
                            <select class="form-select @error('aula_id') is-invalid @enderror" 
                                    id="aula_id" 
                                    name="aula_id">
                                <option value="">Sin asignar</option>
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}" {{ old('aula_id', $equipo->aula_id) == $aula->id ? 'selected' : '' }}>
                                        {{ $aula->nombre }} - {{ $aula->edificio }}
                                    </option>
                                @endforeach
                            </select>
                            @error('aula_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($equipo->estado === 'en_uso')
                                <small class="text-warning">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    El equipo está en uso. No se puede cambiar de aula.
                                </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="ubicacion_especifica" class="form-label">Ubicación Específica</label>
                            <input type="text" 
                                   class="form-control @error('ubicacion_especifica') is-invalid @enderror" 
                                   id="ubicacion_especifica" 
                                   name="ubicacion_especifica" 
                                   value="{{ old('ubicacion_especifica', $equipo->ubicacion_especifica ?? $equipo->ubicacion_en_aula) }}"
                                   placeholder="Ej: Escritorio 5, Mesa frontal">
                            @error('ubicacion_especifica')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Ubicación dentro del aula</small>
                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                      id="observaciones" 
                                      name="observaciones" 
                                      rows="5"
                                      placeholder="Notas adicionales sobre el equipo...">{{ old('observaciones', $equipo->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="activo" 
                                   name="activo" 
                                   value="1"
                                   {{ old('activo', $equipo->activo ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">
                                Equipo activo en el sistema
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.equipos.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-save"></i> Actualizar Equipo
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }
    
    .form-label {
        font-weight: 500;
    }
    
    .text-danger {
        font-weight: bold;
    }
</style>
@endpush
@endsection
