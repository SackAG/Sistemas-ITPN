@extends('layouts.plantilla1')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-pc-display text-primary"></i> Gestión de Equipos
            </h1>
            <p class="text-muted mb-0">Administra el inventario de equipos tecnológicos</p>
        </div>
        <div>
            <a href="{{ route('admin.equipos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Equipo
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-laptop text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0 text-primary">{{ $totalEquipos }}</h3>
                    <p class="text-muted mb-0 small">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0 text-success">{{ $disponibles }}</h3>
                    <p class="text-muted mb-0 small">Disponibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-info">
                <div class="card-body text-center">
                    <i class="bi bi-hourglass-split text-info" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0 text-info">{{ $enUso }}</h3>
                    <p class="text-muted mb-0 small">En Uso</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-tools text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0 text-warning">{{ $enMantenimiento }}</h3>
                    <p class="text-muted mb-0 small">Mantenimiento</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                    <h3 class="mt-2 mb-0 text-danger">{{ $dañados }}</h3>
                    <p class="text-muted mb-0 small">Dañados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.equipos.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nombre, modelo o serie..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="computadora" {{ request('tipo') == 'computadora' ? 'selected' : '' }}>Computadora</option>
                            <option value="proyector" {{ request('tipo') == 'proyector' ? 'selected' : '' }}>Proyector</option>
                            <option value="switch" {{ request('tipo') == 'switch' ? 'selected' : '' }}>Switch</option>
                            <option value="router" {{ request('tipo') == 'router' ? 'selected' : '' }}>Router</option>
                            <option value="impresora" {{ request('tipo') == 'impresora' ? 'selected' : '' }}>Impresora</option>
                            <option value="otro" {{ request('tipo') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="en_uso" {{ request('estado') == 'en_uso' ? 'selected' : '' }}>En Uso</option>
                            <option value="mantenimiento" {{ request('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="dañado" {{ request('estado') == 'dañado' ? 'selected' : '' }}>Dañado</option>
                            <option value="dado_de_baja" {{ request('estado') == 'dado_de_baja' ? 'selected' : '' }}>Dado de Baja</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Aula</label>
                        <select name="aula_id" class="form-select">
                            <option value="">Todas</option>
                            @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}" {{ request('aula_id') == $aula->id ? 'selected' : '' }}>
                                    {{ $aula->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Equipos -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Marca/Modelo</th>
                            <th>Número de Serie</th>
                            <th>Aula</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipos as $equipo)
                            <tr>
                                <td>{{ $equipo->id }}</td>
                                <td>
                                    <strong>{{ $equipo->nombre ?? $equipo->codigo_inventario }}</strong>
                                </td>
                                <td>
                                    @php
                                        $tipoIcons = [
                                            'computadora' => 'bi-laptop',
                                            'proyector' => 'bi-projector',
                                            'switch' => 'bi-hdd-network',
                                            'router' => 'bi-router',
                                            'impresora' => 'bi-printer',
                                            'otro' => 'bi-box'
                                        ];
                                        $icon = $tipoIcons[$equipo->tipo] ?? 'bi-box';
                                    @endphp
                                    <i class="bi {{ $icon }}"></i> {{ ucfirst($equipo->tipo) }}
                                </td>
                                <td>
                                    {{ $equipo->marca ?? 'N/A' }}
                                    @if($equipo->modelo)
                                        <br><small class="text-muted">{{ $equipo->modelo }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $equipo->numero_serie }}</code></td>
                                <td>
                                    @if($equipo->aula)
                                        <span class="badge bg-secondary">{{ $equipo->aula->nombre }}</span>
                                    @else
                                        <span class="text-muted">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $estadoBadge = [
                                            'disponible' => 'success',
                                            'en_uso' => 'info',
                                            'mantenimiento' => 'warning',
                                            'dañado' => 'danger',
                                            'dado_de_baja' => 'secondary'
                                        ];
                                        $badge = $estadoBadge[$equipo->estado] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ str_replace('_', ' ', ucfirst($equipo->estado)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.equipos.edit', $equipo->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.equipos.destroy', $equipo->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de dar de baja este equipo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">No se encontraron equipos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($equipos->hasPages())
            <div class="card-footer">
                {{ $equipos->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush
@endsection
