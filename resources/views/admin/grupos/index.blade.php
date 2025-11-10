@extends('layouts.plantilla1')

@section('title', 'Gestión de Grupos')

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
                        <li class="breadcrumb-item active">Grupos</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-people text-primary"></i>
                            Gestión de Grupos
                        </h2>
                        <p class="text-muted mb-0">Administra los grupos del sistema</p>
                    </div>
                    <a href="{{ route('admin.grupos.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nuevo Grupo
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

        {{-- Estadísticas --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Grupos</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="fs-1 text-primary">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Activos</h6>
                                <h3 class="mb-0">{{ $stats['activos'] }}</h3>
                            </div>
                            <div class="fs-1 text-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Inactivos</h6>
                                <h3 class="mb-0">{{ $stats['inactivos'] }}</h3>
                            </div>
                            <div class="fs-1 text-secondary">
                                <i class="bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Con Alumnos</h6>
                                <h3 class="mb-0">{{ $stats['con_alumnos'] }}</h3>
                            </div>
                            <div class="fs-1 text-info">
                                <i class="bi bi-person-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.grupos.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="materia_id" class="form-label small">Materia</label>
                        <select class="form-select form-select-sm" name="materia_id" id="materia_id">
                            <option value="">Todas las materias</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ request('materia_id') == $materia->id ? 'selected' : '' }}>
                                    {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="profesor_id" class="form-label small">Profesor</label>
                        <select class="form-select form-select-sm" name="profesor_id" id="profesor_id">
                            <option value="">Todos los profesores</option>
                            @foreach($profesores as $profesor)
                                <option value="{{ $profesor->id }}" {{ request('profesor_id') == $profesor->id ? 'selected' : '' }}>
                                    {{ $profesor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="periodo" class="form-label small">Periodo</label>
                        <select class="form-select form-select-sm" name="periodo" id="periodo">
                            <option value="">Todos</option>
                            <option value="Ene-Abr" {{ request('periodo') === 'Ene-Abr' ? 'selected' : '' }}>Ene-Abr</option>
                            <option value="May-Ago" {{ request('periodo') === 'May-Ago' ? 'selected' : '' }}>May-Ago</option>
                            <option value="Sep-Dic" {{ request('periodo') === 'Sep-Dic' ? 'selected' : '' }}>Sep-Dic</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="año" class="form-label small">Año</label>
                        <input type="number" class="form-control form-control-sm" name="año" id="año" 
                               value="{{ request('año') }}" placeholder="Ej: 2025" min="2020" max="2030">
                    </div>
                    <div class="col-md-2">
                        <label for="activo" class="form-label small">Estado</label>
                        <select class="form-select form-select-sm" name="activo" id="activo">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label for="search" class="form-label small">Buscar por clave</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   id="search"
                                   placeholder="Buscar por clave del grupo..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="bi bi-funnel me-1"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.grupos.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Listado de Grupos
                </h5>
            </div>
            <div class="card-body p-0">
                @if($grupos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">Clave</th>
                                    <th width="20%">Materia</th>
                                    <th width="15%">Profesor</th>
                                    <th width="10%" class="text-center">Periodo</th>
                                    <th width="7%" class="text-center">Año</th>
                                    <th width="8%" class="text-center">Estado</th>
                                    <th width="8%" class="text-center">Alumnos</th>
                                    <th width="15%" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grupos as $grupo)
                                    <tr>
                                        <td class="align-middle">
                                            <strong class="text-primary">{{ $grupo->clave_grupo }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            <small>
                                                <i class="bi bi-book me-1"></i>
                                                {{ $grupo->materia->nombre ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="align-middle">
                                            <small>
                                                <i class="bi bi-person-badge me-1"></i>
                                                {{ $grupo->profesor->name ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-info">{{ $grupo->periodo }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-secondary">{{ $grupo->año }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($grupo->activo)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($grupo->alumnos_count > 0)
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-people-fill me-1"></i>
                                                    {{ $grupo->alumnos_count }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-dash-circle me-1"></i>
                                                    0
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-info"
                                                        title="Ver alumnos"
                                                        disabled>
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="{{ route('admin.grupos.edit', $grupo) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $grupo->id }}"
                                                        title="Eliminar"
                                                        @if($grupo->alumnos_count > 0) disabled @endif>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal de confirmación --}}
                                    <div class="modal fade" id="deleteModal{{ $grupo->id }}" tabindex="-1">
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
                                                    <p>¿Estás seguro de que deseas eliminar el grupo <strong>{{ $grupo->clave_grupo }}</strong>?</p>
                                                    <p class="text-danger mb-0">
                                                        <i class="bi bi-exclamation-circle me-1"></i>
                                                        Esta acción no se puede deshacer.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <form action="{{ route('admin.grupos.destroy', $grupo) }}" method="POST" style="display: inline;">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="mt-3 text-muted">No se encontraron grupos.</p>
                        <a href="{{ route('admin.grupos.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Crear Primer Grupo
                        </a>
                    </div>
                @endif
            </div>

            @if($grupos->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando {{ $grupos->firstItem() }} a {{ $grupos->lastItem() }} de {{ $grupos->total() }} grupos
                        </div>
                        {{ $grupos->links() }}
                    </div>
                </div>
            @endif
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
        
        [data-bs-theme="dark"] .table {
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .table-light {
            background-color: #2d3236;
            color: #e8eaed;
        }
    </style>
@endsection
