@extends('layouts.plantilla1')

@section('title', 'Gestión de Aulas')

@section('contenido')
    <div class="container-fluid">
        {{-- Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-building text-primary"></i>
                            Gestión de Aulas
                        </h2>
                        <p class="text-muted mb-0">Administra las aulas del instituto</p>
                    </div>
                    <a href="{{ route('admin.aulas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nueva Aula
                    </a>
                </div>
            </div>
        </div>

        {{-- Mensajes de sesión --}}
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

        {{-- Tarjeta con tabla de aulas --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Listado de Aulas
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Buscar aula...">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                @if($aulas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Nombre</th>
                                    <th>Edificio</th>
                                    <th class="text-center">Capacidad Alumnos</th>
                                    <th class="text-center">Capacidad Equipos</th>
                                    <th class="text-center">Equipos Actuales</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aulas as $aula)
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                                    <i class="bi bi-door-open"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $aula->nombre }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="bi bi-building text-muted me-1"></i>
                                            {{ $aula->edificio }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="bi bi-people-fill me-1"></i>
                                                {{ $aula->capacidad_alumnos }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="bi bi-pc-display me-1"></i>
                                                {{ $aula->capacidad_equipos }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $equiposActuales = $aula->equipos()->count();
                                                $porcentaje = $aula->capacidad_equipos > 0 
                                                    ? ($equiposActuales / $aula->capacidad_equipos * 100) 
                                                    : 0;
                                                $badgeClass = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }}">
                                                {{ $equiposActuales }} / {{ $aula->capacidad_equipos }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($aula->activo)
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
                                        <td class="text-center px-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.aulas.edit', $aula) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $aula->id }}"
                                                        title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            {{-- Modal de confirmación de eliminación --}}
                                            <div class="modal fade" id="deleteModal{{ $aula->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                                                Confirmar eliminación
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-2">¿Estás seguro de que deseas eliminar el aula?</p>
                                                            <div class="alert alert-warning mb-0">
                                                                <strong>{{ $aula->nombre }}</strong> - {{ $aula->edificio }}
                                                            </div>
                                                            <p class="text-muted small mt-3 mb-0">
                                                                <i class="bi bi-info-circle me-1"></i>
                                                                No se puede eliminar si tiene equipos o asignaciones.
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                Cancelar
                                                            </button>
                                                            <form action="{{ route('admin.aulas.destroy', $aula) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="bi bi-trash me-1"></i>
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($aulas->hasPages())
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Mostrando {{ $aulas->firstItem() }} - {{ $aulas->lastItem() }} de {{ $aulas->total() }} aulas
                                </div>
                                <div>
                                    {{ $aulas->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No hay aulas registradas</h4>
                        <p class="text-muted">Comienza agregando la primera aula</p>
                        <a href="{{ route('admin.aulas.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-2"></i>
                            Crear Aula
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.03);
        }
        
        [data-bs-theme="dark"] .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .btn-group .btn {
            padding: 0.375rem 0.75rem;
        }
    </style>
@endsection
