@extends('layouts.plantilla1')

@section('title', 'Gestión de Asignaciones de Aula')

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
                        <li class="breadcrumb-item active">Asignaciones de Aula</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-calendar-week text-primary"></i>
                            Gestión de Asignaciones de Aula
                        </h2>
                        <p class="text-muted mb-0">Administra los horarios de aulas y grupos</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.asignaciones.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Nueva Asignación
                        </a>
                    </div>
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

        {{-- Filtros --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-funnel me-2"></i>
                    Filtros de Búsqueda
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.asignaciones.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="aula_id" class="form-label">Aula</label>
                            <select name="aula_id" id="aula_id" class="form-select">
                                <option value="">-- Todas las aulas --</option>
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}" {{ request('aula_id') == $aula->id ? 'selected' : '' }}>
                                        {{ $aula->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="grupo_id" class="form-label">Grupo</label>
                            <select name="grupo_id" id="grupo_id" class="form-select">
                                <option value="">-- Todos los grupos --</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                        {{ $grupo->clave_grupo }} - {{ $grupo->materia->nombre ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="dia_semana" class="form-label">Día</label>
                            <select name="dia_semana" id="dia_semana" class="form-select">
                                <option value="">-- Todos --</option>
                                @foreach($diasSemana as $dia)
                                    <option value="{{ $dia }}" {{ request('dia_semana') == $dia ? 'selected' : '' }}>
                                        {{ $dia }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="activo" class="form-label">Estado</label>
                            <select name="activo" id="activo" class="form-select">
                                <option value="">-- Todos --</option>
                                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                        </div>
                    </div>

                    @if(request()->hasAny(['aula_id', 'grupo_id', 'dia_semana', 'activo']))
                        <div class="row mt-2">
                            <div class="col-12">
                                <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Limpiar filtros
                                </a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabla de Asignaciones --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Lista de Asignaciones ({{ $asignaciones->total() }})
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">ID</th>
                                <th>Aula</th>
                                <th>Grupo</th>
                                <th>Materia</th>
                                <th>Profesor</th>
                                <th>Día</th>
                                <th>Horario</th>
                                <th>Vigencia</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asignaciones as $asignacion)
                                <tr>
                                    <td class="px-3">{{ $asignacion->id }}</td>
                                    <td>
                                        <i class="bi bi-door-open me-1 text-primary"></i>
                                        <strong>{{ $asignacion->aula->nombre ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $asignacion->grupo->clave_grupo ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $asignacion->grupo->materia->nombre ?? 'N/A' }}</td>
                                    <td>{{ $asignacion->grupo->profesor->name ?? 'N/A' }}</td>
                                    <td>
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $asignacion->dia_semana }}
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1"></i>
                                        {{ substr($asignacion->hora_inicio, 0, 5) }} - 
                                        {{ substr($asignacion->hora_fin, 0, 5) }}
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($asignacion->fecha_inicio_vigencia)->format('d/m/Y') }} <br>
                                            {{ \Carbon\Carbon::parse($asignacion->fecha_fin_vigencia)->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @if($asignacion->activo)
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
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.asignaciones.edit', $asignacion->id) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.asignaciones.destroy', $asignacion->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar esta asignación?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">No hay asignaciones registradas</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($asignaciones->hasPages())
                <div class="card-footer bg-white">
                    {{ $asignaciones->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        [data-bs-theme="dark"] .card {
            background-color: #1a1d20;
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .card-header,
        [data-bs-theme="dark"] .card-footer {
            background-color: #1a1d20 !important;
            border-color: #2d3236;
        }
        
        [data-bs-theme="dark"] .table {
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .table-light {
            background-color: #2d3236;
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #2d3236;
            border-color: #495057;
            color: #e8eaed;
        }
        
        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #2d3236;
            border-color: #86b7fe;
            color: #e8eaed;
        }
    </style>
@endsection
