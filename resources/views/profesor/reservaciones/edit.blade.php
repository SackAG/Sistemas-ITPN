@extends('layouts.plantilla1')

@section('title', 'Editar Reservación')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('profesor.reservaciones.index') }}">Reservaciones</a></li>
                    <li class="breadcrumb-item active">Editar Reservación</li>
                </ol>
            </nav>
            <h2><i class="bi bi-pencil me-2"></i>Editar Reservación</h2>
        </div>
    </div>

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
            <form action="{{ route('profesor.reservaciones.update', $reservacion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="grupo_id" class="form-label">
                            Grupo <span class="text-danger">*</span>
                        </label>
                        <select name="grupo_id" id="grupo_id" class="form-select @error('grupo_id') is-invalid @enderror" required>
                            <option value="">Selecciona un grupo</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->id }}" 
                                    {{ (old('grupo_id', $reservacion->grupo_id) == $grupo->id) ? 'selected' : '' }}>
                                    {{ $grupo->clave_grupo }} - {{ $grupo->materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('grupo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="fecha_reservacion" class="form-label">
                            Fecha <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               name="fecha_reservacion" 
                               id="fecha_reservacion" 
                               class="form-control @error('fecha_reservacion') is-invalid @enderror"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('fecha_reservacion', $reservacion->fecha_reservacion->format('Y-m-d')) }}"
                               onchange="verificarDisponibilidad()"
                               required>
                        @error('fecha_reservacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="hora_inicio" class="form-label">
                            Hora de Inicio <span class="text-danger">*</span>
                        </label>
                        <input type="time" 
                               name="hora_inicio" 
                               id="hora_inicio" 
                               class="form-control @error('hora_inicio') is-invalid @enderror"
                               value="{{ old('hora_inicio', $reservacion->hora_inicio->format('H:i')) }}"
                               onchange="verificarDisponibilidad()"
                               required>
                        @error('hora_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="hora_fin" class="form-label">
                            Hora de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="time" 
                               name="hora_fin" 
                               id="hora_fin" 
                               class="form-control @error('hora_fin') is-invalid @enderror"
                               value="{{ old('hora_fin', $reservacion->hora_fin->format('H:i')) }}"
                               onchange="verificarDisponibilidad()"
                               required>
                        @error('hora_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="aula_id" class="form-label">
                            Aula <span class="text-danger">*</span>
                        </label>
                        <select name="aula_id" id="aula_id" class="form-select @error('aula_id') is-invalid @enderror" onchange="cargarEquipos()" required>
                            <option value="">Selecciona un aula</option>
                            @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}" 
                                    {{ (old('aula_id', $reservacion->aula_id) == $aula->id) ? 'selected' : '' }}>
                                    {{ $aula->nombre }} (Capacidad: {{ $aula->capacidad }})
                                </option>
                            @endforeach
                        </select>
                        @error('aula_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="equipo_id" class="form-label">
                            Equipo <span class="text-danger">*</span>
                        </label>
                        <select name="equipo_id" id="equipo_id" class="form-select @error('equipo_id') is-invalid @enderror" required>
                            <option value="">Selecciona un equipo</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}" 
                                    {{ (old('equipo_id', $reservacion->equipo_id) == $equipo->id) ? 'selected' : '' }}>
                                    {{ $equipo->nombre }} - {{ $equipo->codigo_inventario }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="equipoInfo" class="mt-2"></div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="motivo" class="form-label">
                            Motivo de la Reservación <span class="text-danger">*</span>
                        </label>
                        <textarea name="motivo" 
                                  id="motivo" 
                                  rows="3" 
                                  class="form-control @error('motivo') is-invalid @enderror"
                                  maxlength="500"
                                  required>{{ old('motivo', $reservacion->motivo) }}</textarea>
                        <small class="form-text text-muted">Máximo 500 caracteres</small>
                        @error('motivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="border-top pt-3 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Actualizar Reservación
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
    const equipoActual = {{ $reservacion->equipo_id }};

    if (!aulaId || !fecha || !horaInicio || !horaFin) {
        return;
    }

    equipoSelect.innerHTML = '<option value="">Cargando...</option>';
    equipoInfo.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Verificando...';

    fetch(`{{ route('profesor.reservaciones.equipos-aula') }}?aula_id=${aulaId}&fecha=${fecha}&hora_inicio=${horaInicio}&hora_fin=${horaFin}`)
        .then(response => response.json())
        .then(data => {
            equipoSelect.innerHTML = '<option value="">Selecciona un equipo</option>';
            
            data.forEach(equipo => {
                const option = document.createElement('option');
                option.value = equipo.id;
                option.textContent = `${equipo.nombre} - ${equipo.codigo_inventario}`;
                if (equipo.id === equipoActual) {
                    option.selected = true;
                }
                equipoSelect.appendChild(option);
            });

            equipoInfo.innerHTML = data.length > 0 
                ? `<div class="alert alert-success py-2 mb-0"><i class="bi bi-check-circle me-1"></i>${data.length} equipo(s) disponible(s)</div>`
                : '<div class="alert alert-warning py-2 mb-0"><i class="bi bi-exclamation-triangle me-1"></i>No hay equipos disponibles</div>';
        })
        .catch(error => {
            console.error('Error:', error);
            equipoInfo.innerHTML = '<div class="alert alert-danger py-2 mb-0">Error al verificar disponibilidad</div>';
        });
}
</script>
@endsection
