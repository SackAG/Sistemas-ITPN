@extends('layouts.plantilla1')

@section('title', 'Gestión de Materias')

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
                        <li class="breadcrumb-item active">Materias</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-book text-primary"></i>
                            Gestión de Materias
                        </h2>
                        <p class="text-muted mb-0">Administra las materias del sistema</p>
                    </div>
                    <a href="{{ route('admin.materias.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nueva Materia
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
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Materias</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="fs-1 text-primary">
                                <i class="bi bi-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Con Grupos</h6>
                                <h3 class="mb-0">{{ $stats['con_grupos'] }}</h3>
                            </div>
                            <div class="fs-1 text-success">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Sin Grupos</h6>
                                <h3 class="mb-0">{{ $stats['sin_grupos'] }}</h3>
                            </div>
                            <div class="fs-1 text-secondary">
                                <i class="bi bi-inbox"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.materias.index') }}" method="GET" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Buscar por nombre o clave de materia..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-2"></i>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Listado de Materias
                </h5>
            </div>
            <div class="card-body p-0">
                @if($materias->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="8%">Clave</th>
                                    <th width="20%">Nombre</th>
                                    <th width="18%">Carrera</th>
                                    <th width="8%" class="text-center">Sem.</th>
                                    <th width="20%">Descripción</th>
                                    <th width="8%" class="text-center">Grupos</th>
                                    <th width="10%" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materias as $materia)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">{{ $materia->id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-info">{{ $materia->clave }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <strong>{{ $materia->nombre }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            <small class="text-muted">
                                                <i class="bi bi-mortarboard me-1"></i>
                                                {{ $materia->carrera->nombre ?? 'Sin carrera' }}
                                            </small>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-primary">{{ $materia->semestre }}°</span>
                                        </td>
                                        <td class="align-middle">
                                            @if($materia->descripcion)
                                                <small class="text-muted">{{ Str::limit($materia->descripcion, 40) }}</small>
                                            @else
                                                <small class="text-muted fst-italic">Sin descripción</small>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($materia->grupos_count > 0)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-people-fill me-1"></i>
                                                    {{ $materia->grupos_count }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-dash-circle me-1"></i>
                                                    Sin grupos
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.materias.edit', $materia) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $materia->id }}"
                                                        title="Eliminar"
                                                        @if($materia->grupos_count > 0) disabled @endif>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal de confirmación --}}
                                    <div class="modal fade" id="deleteModal{{ $materia->id }}" tabindex="-1">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="mt-3 text-muted">No se encontraron materias.</p>
                        <a href="{{ route('admin.materias.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Crear Primera Materia
                        </a>
                    </div>
                @endif
            </div>

            @if($materias->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando {{ $materias->firstItem() }} a {{ $materias->lastItem() }} de {{ $materias->total() }} materias
                        </div>
                        {{ $materias->links() }}
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
