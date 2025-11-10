@extends('layouts.plantilla1')

@section('title', 'Gestión de Alumnos')

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
                        <li class="breadcrumb-item active">Alumnos</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-people-fill text-primary"></i>
                            Gestión de Alumnos - Grupo {{ $grupo->clave_grupo }}
                        </h2>
                        <p class="text-muted mb-0">
                            <strong>Materia:</strong> {{ $grupo->materia->nombre ?? 'N/A' }} | 
                            <strong>Profesor:</strong> {{ $grupo->profesor->name ?? 'N/A' }} | 
                            <strong>Periodo:</strong> {{ $grupo->periodo }} {{ $grupo->año }}
                        </p>
                    </div>
                    <a href="{{ route('admin.grupos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Volver a Grupos
                    </a>
                </div>
            </div>
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card: Alumnos Inscritos --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-check me-2"></i>
                    Alumnos Inscritos en el Grupo
                </h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarAlumno">
                    <i class="bi bi-plus-circle me-2"></i>
                    Agregar Alumno
                </button>
            </div>
            <div class="card-body p-0">
                @if($grupo->alumnos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="15%">Número de Control</th>
                                    <th width="25%">Nombre del Alumno</th>
                                    <th width="20%">Carrera</th>
                                    <th width="12%" class="text-center">Fecha Inscripción</th>
                                    <th width="10%" class="text-center">Estado</th>
                                    <th width="13%" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grupo->alumnos as $alumno)
                                    <tr>
                                        <td class="align-middle">{{ $alumno->id }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">
                                                {{ $alumno->no_ctrl ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <i class="bi bi-person-circle me-1"></i>
                                            {{ $alumno->name }}
                                        </td>
                                        <td class="align-middle">
                                            <small>{{ $alumno->carrera->nombre ?? 'Sin carrera' }}</small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <small>
                                                {{ $alumno->pivot->fecha_inscripcion ? \Carbon\Carbon::parse($alumno->pivot->fecha_inscripcion)->format('d/m/Y') : 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($alumno->pivot->activo)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                {{-- Toggle activo/inactivo --}}
                                                <form action="{{ route('admin.grupos.alumnos.toggle', [$grupo->id, $alumno->id]) }}" 
                                                      method="POST" 
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $alumno->pivot->activo ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                            title="{{ $alumno->pivot->activo ? 'Desactivar' : 'Activar' }}">
                                                        <i class="bi {{ $alumno->pivot->activo ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                    </button>
                                                </form>

                                                {{-- Remover alumno --}}
                                                <form action="{{ route('admin.grupos.alumnos.remover', [$grupo->id, $alumno->id]) }}" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('¿Estás seguro de remover a este alumno del grupo?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Remover">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="mt-3 text-muted mb-0">No hay alumnos inscritos en este grupo</p>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">
                        <strong>Total de alumnos:</strong> {{ $grupo->alumnos->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Agregar Alumno al Grupo --}}
    <div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarAlumnoLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        Agregar Alumno al Grupo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.grupos.alumnos.agregar', $grupo->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if($alumnosDisponibles->count() > 0)
                            <div class="mb-3">
                                <label for="alumno_id" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Seleccionar Alumno <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('alumno_id') is-invalid @enderror" 
                                        id="alumno_id" 
                                        name="alumno_id" 
                                        required>
                                    <option value="">Seleccionar alumno...</option>
                                    @foreach($alumnosDisponibles as $alumno)
                                        <option value="{{ $alumno->id }}" {{ old('alumno_id') == $alumno->id ? 'selected' : '' }}>
                                            {{ $alumno->no_ctrl ?? 'S/N' }} - {{ $alumno->name }} ({{ $alumno->carrera->nombre ?? 'Sin carrera' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('alumno_id')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Selecciona el alumno que deseas agregar al grupo
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>No hay alumnos disponibles para agregar.</strong>
                                <p class="mb-0 mt-2">Todos los alumnos ya están inscritos en este grupo o no hay alumnos registrados en el sistema.</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancelar
                        </button>
                        @if($alumnosDisponibles->count() > 0)
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Agregar
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #1a1d20;
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .card-header {
            background-color: #1a1d20 !important;
            border-bottom-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .card-header.bg-white {
            background-color: #1a1d20 !important;
        }
        
        [data-bs-theme="dark"] .card-footer.bg-white {
            background-color: #1a1d20 !important;
            border-top-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .table {
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .table-light {
            background-color: #2d3236;
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .modal-content {
            background-color: #1a1d20;
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .modal-header {
            border-bottom-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .modal-footer {
            border-top-color: #2d3236;
        }
    </style>
@endsection
