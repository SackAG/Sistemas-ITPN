@extends('layouts.plantilla1')

@section('title', 'Gestión de Usuarios')

@section('contenido')
    <div class="container-fluid">
        {{-- Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-person-badge text-primary"></i>
                            Gestión de Usuarios
                        </h2>
                        <p class="text-muted mb-0">Administra los usuarios del sistema</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nuevo Usuario
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

        {{-- Estadísticas --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill fs-1 text-primary mb-2"></i>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        <small class="text-muted">Total Usuarios</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check fs-1 text-danger mb-2"></i>
                        <h3 class="mb-0">{{ $stats['admins'] }}</h3>
                        <small class="text-muted">Administradores</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person-workspace fs-1 text-info mb-2"></i>
                        <h3 class="mb-0">{{ $stats['profesores'] }}</h3>
                        <small class="text-muted">Profesores</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person-video3 fs-1 text-success mb-2"></i>
                        <h3 class="mb-0">{{ $stats['alumnos'] }}</h3>
                        <small class="text-muted">Alumnos</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta con filtros y tabla --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-3">
                    <i class="bi bi-funnel me-2"></i>
                    Filtros y Búsqueda
                </h5>
                
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                    {{-- Búsqueda --}}
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Buscar por nombre, email o número de control..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Filtro por rol --}}
                    <div class="col-md-3">
                        <select class="form-select" name="rol">
                            <option value="">Todos los roles</option>
                            <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>
                                Administrador
                            </option>
                            <option value="profesor" {{ request('rol') === 'profesor' ? 'selected' : '' }}>
                                Profesor
                            </option>
                            <option value="alumno" {{ request('rol') === 'alumno' ? 'selected' : '' }}>
                                Alumno
                            </option>
                        </select>
                    </div>

                    {{-- Botones --}}
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Buscar
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="card-body p-0">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Usuario</th>
                                    <th>Rol</th>
                                    <th>No. Control</th>
                                    <th>Carrera</th>
                                    <th>Registro</th>
                                    <th class="text-center px-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-{{ $user->rol === 'admin' ? 'danger' : ($user->rol === 'profesor' ? 'info' : 'success') }} bg-opacity-10 text-{{ $user->rol === 'admin' ? 'danger' : ($user->rol === 'profesor' ? 'info' : 'success') }} me-3">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->rol === 'admin')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-shield-check me-1"></i>
                                                    Administrador
                                                </span>
                                            @elseif($user->rol === 'profesor')
                                                <span class="badge bg-info">
                                                    <i class="bi bi-person-workspace me-1"></i>
                                                    Profesor
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-person-video3 me-1"></i>
                                                    Alumno
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->no_ctrl)
                                                <code>{{ $user->no_ctrl }}</code>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->carrera)
                                                <small class="text-muted">{{ $user->carrera->nombre }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $user->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center px-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#resetPasswordModal{{ $user->id }}"
                                                        title="Resetear contraseña">
                                                    <i class="bi bi-key"></i>
                                                </button>
                                                @if($user->id !== auth()->id())
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal{{ $user->id }}"
                                                            title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            {{-- Modal de resetear contraseña --}}
                                            <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-key text-warning me-2"></i>
                                                                Resetear Contraseña
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <p class="mb-3">
                                                                    Resetear contraseña para: <strong>{{ $user->name }}</strong>
                                                                </p>
                                                                <div class="mb-3">
                                                                    <label for="new_password{{ $user->id }}" class="form-label">
                                                                        Nueva Contraseña
                                                                    </label>
                                                                    <input type="password" 
                                                                           class="form-control" 
                                                                           id="new_password{{ $user->id }}"
                                                                           name="new_password"
                                                                           required
                                                                           minlength="8">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="new_password_confirmation{{ $user->id }}" class="form-label">
                                                                        Confirmar Contraseña
                                                                    </label>
                                                                    <input type="password" 
                                                                           class="form-control" 
                                                                           id="new_password_confirmation{{ $user->id }}"
                                                                           name="new_password_confirmation"
                                                                           required
                                                                           minlength="8">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-warning">
                                                                    <i class="bi bi-key me-1"></i>
                                                                    Resetear Contraseña
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal de confirmación de eliminación --}}
                                            @if($user->id !== auth()->id())
                                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
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
                                                                <p class="mb-2">¿Estás seguro de que deseas eliminar este usuario?</p>
                                                                <div class="alert alert-warning mb-0">
                                                                    <strong>{{ $user->name }}</strong><br>
                                                                    <small>{{ $user->email }}</small>
                                                                </div>
                                                                @if($user->rol === 'profesor')
                                                                    <p class="text-muted small mt-3 mb-0">
                                                                        <i class="bi bi-info-circle me-1"></i>
                                                                        No se puede eliminar si tiene grupos asignados.
                                                                    </p>
                                                                @elseif($user->rol === 'alumno')
                                                                    <p class="text-muted small mt-3 mb-0">
                                                                        <i class="bi bi-info-circle me-1"></i>
                                                                        No se puede eliminar si está inscrito en grupos.
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer border-0">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    Cancelar
                                                                </button>
                                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
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
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($users->hasPages())
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de {{ $users->total() }} usuarios
                                </div>
                                <div>
                                    {{ $users->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-person-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No se encontraron usuarios</h4>
                        <p class="text-muted">
                            @if(request('search') || request('rol'))
                                Intenta ajustar los filtros de búsqueda
                            @else
                                Comienza agregando el primer usuario
                            @endif
                        </p>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-2"></i>
                            Crear Usuario
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
