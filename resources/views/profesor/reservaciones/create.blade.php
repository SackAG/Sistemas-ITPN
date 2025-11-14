@extends('layouts.plantilla1')

@section('title', 'Nueva Reservación')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('profesor.reservaciones.index') }}">Reservaciones</a></li>
                    <li class="breadcrumb-item active">Nueva Reservación</li>
                </ol>
            </nav>
            <h2><i class="bi bi-plus-circle me-2"></i>Nueva Reservación de Equipo</h2>
        </div>
    </div>

    <!-- Mensajes de error -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5><i class="bi bi-exclamation-triangle me-2"></i>Errores en el formulario:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('profesor.reservaciones.store') }}" method="POST" id="formReservacion">
                @csrf

                <div class="row">
                    <!-- Grupo -->
                    <div class="col-md-6 mb-3">
                        <label for="grupo_id" class="form-label">
                            Grupo <span class="text-danger">*</span>
                        </label>
                        <select name="grupo_id" id="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror" required>
                            <option value="">Selecciona un grupo</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                    {{ $grupo->clave_grupo }} - {{ $grupo->materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('grupo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_reservacion" class="form-label">
                            Fecha <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               name="fecha_reservacion" 
                               id="fecha_reservacion" 
                               class="form-control @error('fecha_reservacion') is-invalid @enderror"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('fecha_reservacion') }}"
                               onchange="verificarDisponibilidad()"
                               required>
                        @error('fecha_reservacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora Inicio -->
                    <div class="col-md-6 mb-3">
                        <label for="hora_inicio" class="form-label">
                            Hora de Inicio <span class="text-danger">*</span>
                        </label>
                        <input type="time" 
                               name="hora_inicio" 
                               id="hora_inicio" 
                               class="form-control @error('hora_inicio') is-invalid @enderror"
                               value="{{ old('hora_inicio') }}"
                               onchange="verificarDisponibilidad()"
                               required>
                        @error('hora_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hora Fin -->
                    <div class="col-md-6 mb-3">
                        <label for="hora_fin" class="form-label">
                            Hora de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="time" 
                               name="hora_fin" 
                               id="hora_fin" 
                               class="form-control @error('hora_fin') is-invalid @enderror"
                               value="{{ old('hora_fin') }}"
                               onchange="verificarDisponibilidad()"
                               required>
                        @error('hora_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Aula -->
                    <div class="col-md-6 mb-3">
                        <label for="aula_id" class="form-label">
                            Aula <span class="text-danger">*</span>
                        </label>
                        <select name="aula_id" id="aula_id" class="form-select @error('aula_id') is-invalid @enderror" onchange="cargarEquipos()" required>
                            <option value="">Selecciona un aula</option>
                            @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}" {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                    {{ $aula->nombre }} (Capacidad: {{ $aula->capacidad }})
                                </option>
                            @endforeach
                        </select>
                        @error('aula_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Equipo -->
                    <div class="col-md-6 mb-3">
                        <label for="equipo_id" class="form-label">
                            Equipo <span class="text-danger">*</span>
                        </label>
                        <select name="equipo_id" id="equipo_id" class="form-select @error('equipo_id') is-invalid @enderror" required>
                            <option value="">Primero selecciona aula, fecha y horario</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}" {{ old('equipo_id') == $equipo->id ? 'selected' : '' }}>
                                    {{ $equipo->nombre }} - {{ $equipo->codigo_inventario }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="equipoInfo" class="mt-2"></div>
                    </div>

                    <!-- Motivo -->
                    <div class="col-12 mb-3">
                        <label for="motivo" class="form-label">
                            Motivo de la Reservación <span class="text-danger">*</span>
                        </label>
                        <textarea name="motivo" 
                                  id="motivo" 
                                  rows="3" 
                                  class="form-control @error('motivo') is-invalid @enderror"
                                  maxlength="500"
                                  placeholder="Describe el motivo de la reservación..."
                                  required>{{ old('motivo') }}</textarea>
                        <small class="form-text text-muted">Máximo 500 caracteres</small>
                        @error('motivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-3 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Crear Reservación
                    </button>
                    <a href="{{ route('profesor.reservaciones.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function verificarDisponibilidad() {
    cargarEquipos();
}

function cargarEquipos() {
    const aulaId = document.getElementById('aula_id').value;
    const fecha = document.getElementById('fecha_reservacion').value;
    const horaInicio = document.getElementById('hora_inicio').value;
    const horaFin = document.getElementById('hora_fin').value;
    const equipoSelect = document.getElementById('equipo_id');
    const equipoInfo = document.getElementById('equipoInfo');

    if (!aulaId || !fecha || !horaInicio || !horaFin) {
        equipoSelect.innerHTML = '<option value="">Completa todos los campos de fecha y horario</option>';
        equipoInfo.innerHTML = '';
        return;
    }

    // Mostrar loading
    equipoSelect.innerHTML = '<option value="">Cargando equipos disponibles...</option>';
    equipoInfo.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Verificando disponibilidad...';

    // Hacer petición AJAX
    fetch(`{{ route('profesor.reservaciones.equipos-aula') }}?aula_id=${aulaId}&fecha=${fecha}&hora_inicio=${horaInicio}&hora_fin=${horaFin}`)
        .then(response => response.json())
        .then(data => {
            equipoSelect.innerHTML = '<option value="">Selecciona un equipo</option>';
            
            if (data.length > 0) {
                data.forEach(equipo => {
                    const option = document.createElement('option');
                    option.value = equipo.id;
                    option.textContent = `${equipo.nombre} - ${equipo.codigo_inventario} (${equipo.tipo})`;
                    equipoSelect.appendChild(option);
                });
                equipoInfo.innerHTML = `<div class="alert alert-success py-2 mb-0"><i class="bi bi-check-circle me-1"></i>${data.length} equipo(s) disponible(s)</div>`;
            } else {
                equipoSelect.innerHTML = '<option value="">No hay equipos disponibles</option>';
                equipoInfo.innerHTML = '<div class="alert alert-warning py-2 mb-0"><i class="bi bi-exclamation-triangle me-1"></i>No hay equipos disponibles para este horario</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            equipoSelect.innerHTML = '<option value="">Error al cargar equipos</option>';
            equipoInfo.innerHTML = '<div class="alert alert-danger py-2 mb-0"><i class="bi bi-x-circle me-1"></i>Error al verificar disponibilidad</div>';
        });
}
</script>
@endsection
