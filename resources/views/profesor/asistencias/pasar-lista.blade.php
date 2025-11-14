@extends('layouts.plantilla1')

@section('contenido')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-clipboard-check text-success"></i> Pasar Lista
            </h1>
            <p class="text-muted mb-0">{{ $grupo->clave_grupo }} - {{ $grupo->materia->nombre }}</p>
        </div>
        <div>
            <a href="{{ route('profesor.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    <form action="{{ route('profesor.asistencias.guardar', $grupo->id) }}" method="POST" id="formAsistencia">
        @csrf
        <input type="hidden" name="grupo_id" value="{{ $grupo->id }}">
        <input type="hidden" name="fecha" value="{{ $fecha }}">
        
        <!-- Información de la Sesión -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-info-circle text-primary"></i> Información del Grupo
                        </h5>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <small class="text-muted">Grupo:</small>
                                <p class="mb-0 fw-semibold">{{ $grupo->clave_grupo }}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Materia:</small>
                                <p class="mb-0 fw-semibold">{{ $grupo->materia->nombre }}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Periodo:</small>
                                <p class="mb-0 fw-semibold">{{ $grupo->periodo }} {{ $grupo->año }}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Total Alumnos:</small>
                                <p class="mb-0 fw-semibold">{{ $grupo->alumnos->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-calendar-event text-info"></i> Información de la Clase
                        </h5>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Fecha:</label>
                                <input type="date" class="form-control" value="{{ $fecha }}" readonly>
                            </div>
                            @if($asignacionAula)
                                <input type="hidden" name="aula_id" value="{{ $asignacionAula->aula_id }}">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Aula Asignada:</small>
                                    <p class="mb-0 fw-semibold">
                                        <i class="bi bi-building"></i> {{ $asignacionAula->aula->nombre }}
                                    </p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Horario:</small>
                                    <p class="mb-0 fw-semibold">
                                        {{ date('H:i', strtotime($asignacionAula->hora_inicio)) }} - 
                                        {{ date('H:i', strtotime($asignacionAula->hora_fin)) }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Hora Entrada:</label>
                                    <input type="time" name="hora_entrada" class="form-control" 
                                           value="{{ date('H:i') }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Hora Salida:</label>
                                    <input type="time" name="hora_salida" class="form-control" 
                                           value="{{ date('H:i', strtotime('+50 minutes')) }}">
                                </div>
                            @else
                                <div class="col-12">
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        No hay aula asignada para hoy.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($asistenciasExistentes->count() > 0)
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                <strong>Ya existe un registro de asistencia para hoy.</strong> 
                Puedes editar los registros existentes.
            </div>
        @endif

        <!-- Controles de Asistencia Rápida -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="bi bi-lightning-charge text-warning"></i> Acciones Rápidas
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success" onclick="marcarTodos('presente')">
                        <i class="bi bi-check-all"></i> Todos Presentes
                    </button>
                    <button type="button" class="btn btn-danger" onclick="marcarTodos('ausente')">
                        <i class="bi bi-x-circle"></i> Todos Ausentes
                    </button>
                    <button type="button" class="btn btn-warning" onclick="marcarTodos('retardo')">
                        <i class="bi bi-clock"></i> Todos Retardo
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="limpiarTodos()">
                        <i class="bi bi-eraser"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Alumnos -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill"></i> Lista de Alumnos ({{ $grupo->alumnos->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 120px;">Núm. Control</th>
                                <th>Nombre Completo</th>
                                <th style="width: 150px;">Carrera</th>
                                <th class="text-center" style="width: 250px;">Estado</th>
                                <th style="width: 200px;">Equipo</th>
                                <th style="width: 200px;">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grupo->alumnos as $index => $alumno)
                                @php
                                    $asistenciaExistente = $asistenciasExistentes->get($alumno->id);
                                @endphp
                                <tr class="alumno-row" data-alumno-id="{{ $alumno->id }}">
                                    <td class="align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary">{{ $alumno->numero_control ?? 'N/A' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <strong>{{ $alumno->name }}</strong>
                                        <br><small class="text-muted">{{ $alumno->email }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $alumno->carrera->nombre ?? 'N/A' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <input type="hidden" name="asistencias[{{ $index }}][alumno_id]" value="{{ $alumno->id }}">
                                        <div class="btn-group btn-group-sm estado-grupo" role="group">
                                            <input type="radio" class="btn-check" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   id="presente_{{ $alumno->id }}" 
                                                   value="presente"
                                                   {{ $asistenciaExistente && $asistenciaExistente->estado == 'presente' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success" for="presente_{{ $alumno->id }}">
                                                <i class="bi bi-check-circle"></i> Presente
                                            </label>

                                            <input type="radio" class="btn-check" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   id="ausente_{{ $alumno->id }}" 
                                                   value="ausente"
                                                   {{ $asistenciaExistente && $asistenciaExistente->estado == 'ausente' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="ausente_{{ $alumno->id }}">
                                                <i class="bi bi-x-circle"></i> Ausente
                                            </label>

                                            <input type="radio" class="btn-check" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   id="retardo_{{ $alumno->id }}" 
                                                   value="retardo"
                                                   {{ $asistenciaExistente && $asistenciaExistente->estado == 'retardo' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning" for="retardo_{{ $alumno->id }}">
                                                <i class="bi bi-clock"></i> Retardo
                                            </label>

                                            <input type="radio" class="btn-check" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   id="justificado_{{ $alumno->id }}" 
                                                   value="justificado"
                                                   {{ $asistenciaExistente && $asistenciaExistente->estado == 'justificado' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-info" for="justificado_{{ $alumno->id }}">
                                                <i class="bi bi-shield-check"></i> Justificado
                                            </label>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column gap-2">
                                            <select name="asistencias[{{ $index }}][tipo_equipo_usado]" 
                                                    class="form-select form-select-sm tipo-equipo-select" 
                                                    data-index="{{ $index }}"
                                                    onchange="toggleEquipoSelect({{ $index }})">
                                                <option value="escuela" {{ $asistenciaExistente && $asistenciaExistente->tipo_equipo_usado == 'escuela' ? 'selected' : '' }}>
                                                    Equipo de la Escuela
                                                </option>
                                                <option value="propio" {{ $asistenciaExistente && $asistenciaExistente->tipo_equipo_usado == 'propio' ? 'selected' : '' }}>
                                                    Equipo Propio
                                                </option>
                                            </select>
                                            
                                            @if($asignacionAula && $asignacionAula->aula->equipos->where('activo', true)->count() > 0)
                                                <select name="asistencias[{{ $index }}][equipo_id]" 
                                                        id="equipo_select_{{ $index }}"
                                                        class="form-select form-select-sm equipo-select" 
                                                        style="display: {{ $asistenciaExistente && $asistenciaExistente->tipo_equipo_usado == 'escuela' ? 'block' : 'none' }};">
                                                    <option value="">Sin asignar</option>
                                                    @foreach($asignacionAula->aula->equipos->where('activo', true) as $equipo)
                                                        <option value="{{ $equipo->id }}"
                                                                {{ $asistenciaExistente && $asistenciaExistente->equipo_id == $equipo->id ? 'selected' : '' }}>
                                                            {{ $equipo->nombre }} - {{ $equipo->codigo_inventario }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <small class="text-muted">No hay equipos en el aula</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <textarea name="asistencias[{{ $index }}][observaciones]" 
                                                  class="form-control form-control-sm" 
                                                  rows="1" 
                                                  placeholder="Opcional">{{ $asistenciaExistente->observaciones ?? '' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('profesor.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-save"></i> Guardar Asistencias
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }
    
    .btn-check:checked + .btn-outline-success {
        background-color: #198754;
        color: white;
    }
    
    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-check:checked + .btn-outline-warning {
        background-color: #ffc107;
        color: #000;
    }
    
    .btn-check:checked + .btn-outline-info {
        background-color: #0dcaf0;
        color: #000;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .alumno-row {
        transition: background-color 0.2s ease;
    }
    
    @media (max-width: 768px) {
        .estado-grupo {
            flex-direction: column;
        }
        
        .estado-grupo .btn {
            border-radius: 0.25rem !important;
            margin-bottom: 0.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Función para mostrar/ocultar el selector de equipo
    function toggleEquipoSelect(index) {
        const tipoSelect = document.querySelector(`select[name="asistencias[${index}][tipo_equipo_usado]"]`);
        const equipoSelect = document.getElementById(`equipo_select_${index}`);
        
        if (equipoSelect) {
            if (tipoSelect.value === 'escuela') {
                equipoSelect.style.display = 'block';
            } else {
                equipoSelect.style.display = 'none';
                equipoSelect.value = ''; // Limpiar selección
            }
        }
    }
    
    // Inicializar estado de los selectores al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        const tipoSelects = document.querySelectorAll('.tipo-equipo-select');
        tipoSelects.forEach(select => {
            const index = select.dataset.index;
            toggleEquipoSelect(index);
        });
    });
    
    // Función para marcar todos los alumnos con un estado
    function marcarTodos(estado) {
        const radios = document.querySelectorAll(`input[type="radio"][value="${estado}"]`);
        radios.forEach(radio => {
            radio.checked = true;
        });
    }
    
    // Función para limpiar todas las selecciones
    function limpiarTodos() {
        const radios = document.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            radio.checked = false;
        });
        
        const textareas = document.querySelectorAll('textarea[name*="observaciones"]');
        textareas.forEach(textarea => {
            textarea.value = '';
        });
    }
    
    // Validar formulario antes de enviar
    document.getElementById('formAsistencia').addEventListener('submit', function(e) {
        const radios = document.querySelectorAll('input[type="radio"]:checked');
        
        if (radios.length === 0) {
            e.preventDefault();
            alert('Por favor, marca el estado de al menos un alumno antes de guardar.');
            return false;
        }
        
        // Confirmar guardado
        if (!confirm(`¿Estás seguro de guardar la asistencia de ${radios.length} alumno(s)?`)) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endpush
@endsection
