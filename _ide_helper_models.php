<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $aula_id
 * @property int $grupo_id
 * @property string $dia_semana
 * @property \Illuminate\Support\Carbon $hora_inicio
 * @property \Illuminate\Support\Carbon $hora_fin
 * @property \Illuminate\Support\Carbon $fecha_inicio_vigencia
 * @property \Illuminate\Support\Carbon $fecha_fin_vigencia
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Aula $aula
 * @property-read \App\Models\Grupo $grupo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SesionClase> $sesiones
 * @property-read int|null $sesiones_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereDiaSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereFechaFinVigencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereFechaInicioVigencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AsignacionAula whereUpdatedAt($value)
 */
	class AsignacionAula extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sesion_clase_id
 * @property int $alumno_id
 * @property int|null $equipo_id
 * @property \Illuminate\Support\Carbon $hora_entrada
 * @property \Illuminate\Support\Carbon|null $hora_salida
 * @property bool $asistio
 * @property bool $uso_equipo_personal
 * @property string|null $observaciones
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $alumno
 * @property-read \App\Models\Equipo|null $equipo
 * @property-read \App\Models\SesionClase $sesionClase
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereAlumnoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereAsistio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereEquipoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereHoraEntrada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereHoraSalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereSesionClaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asistencia whereUsoEquipoPersonal($value)
 */
	class Asistencia extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string $edificio
 * @property int $capacidad_alumnos
 * @property int $capacidad_equipos
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AsignacionAula> $asignaciones
 * @property-read int|null $asignaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Equipo> $equipos
 * @property-read int|null $equipos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistorialUsoAula> $historialUsos
 * @property-read int|null $historial_usos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservacionEquipo> $reservaciones
 * @property-read int|null $reservaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsoLibreEquipo> $usosLibres
 * @property-read int|null $usos_libres_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereCapacidadAlumnos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereCapacidadEquipos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereEdificio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Aula whereUpdatedAt($value)
 */
	class Aula extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string $clave
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Materia> $materias
 * @property-read int|null $materias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereClave($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carrera whereUpdatedAt($value)
 */
	class Carrera extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $codigo_inventario
 * @property string $tipo
 * @property string $marca
 * @property string $modelo
 * @property string|null $numero_serie
 * @property string $estado
 * @property int|null $aula_id
 * @property string|null $ubicacion_en_aula
 * @property string $propiedad
 * @property int|null $propietario_id
 * @property \Illuminate\Support\Carbon|null $fecha_adquisicion
 * @property string|null $observaciones
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asistencia> $asistencias
 * @property-read int|null $asistencias_count
 * @property-read \App\Models\Aula|null $aula
 * @property-read \App\Models\User|null $propietario
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservacionEquipo> $reservaciones
 * @property-read int|null $reservaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsoLibreEquipo> $usosLibres
 * @property-read int|null $usos_libres_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereCodigoInventario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereFechaAdquisicion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereMarca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereModelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereNumeroSerie($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo wherePropiedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo wherePropietarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereUbicacionEnAula($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Equipo whereUpdatedAt($value)
 */
	class Equipo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $materia_id
 * @property int $profesor_id
 * @property string $clave_grupo
 * @property string $periodo
 * @property int $año
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $alumnos
 * @property-read int|null $alumnos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AsignacionAula> $asignaciones
 * @property-read int|null $asignaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistorialUsoAula> $historialUsos
 * @property-read int|null $historial_usos_count
 * @property-read \App\Models\Materia $materia
 * @property-read \App\Models\User $profesor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservacionEquipo> $reservaciones
 * @property-read int|null $reservaciones_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereAño($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereClaveGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereMateriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereProfesorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereUpdatedAt($value)
 */
	class Grupo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $grupo_id
 * @property int $alumno_id
 * @property \Illuminate\Support\Carbon $fecha_inscripcion
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $alumno
 * @property-read \App\Models\Grupo $grupo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno whereAlumnoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno whereFechaInscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoAlumno whereUpdatedAt($value)
 */
	class GrupoAlumno extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $aula_id
 * @property string $tipo_uso
 * @property \Illuminate\Support\Carbon $fecha
 * @property \Illuminate\Support\Carbon $hora_inicio
 * @property \Illuminate\Support\Carbon|null $hora_fin
 * @property int $usuario_id
 * @property int|null $grupo_id
 * @property int|null $sesion_clase_id
 * @property int|null $uso_libre_id
 * @property int|null $reservacion_id
 * @property string|null $descripcion
 * @property string|null $observaciones
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Aula $aula
 * @property-read \App\Models\Grupo|null $grupo
 * @property-read \App\Models\ReservacionEquipo|null $reservacion
 * @property-read \App\Models\SesionClase|null $sesionClase
 * @property-read \App\Models\UsoLibreEquipo|null $usoLibre
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereReservacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereSesionClaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereTipoUso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereUsoLibreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialUsoAula whereUsuarioId($value)
 */
	class HistorialUsoAula extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string $clave
 * @property int $carrera_id
 * @property int $semestre
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Carrera $carrera
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grupo> $grupos
 * @property-read int|null $grupos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tema> $temas
 * @property-read int|null $temas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereCarreraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereClave($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereSemestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materia whereUpdatedAt($value)
 */
	class Materia extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $alumno
 * @property-read \App\Models\Aula|null $aula
 * @property-read \App\Models\Equipo|null $equipo
 * @property-read \App\Models\Grupo|null $grupo
 * @property-read \App\Models\HistorialUsoAula|null $historial
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservacionEquipo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservacionEquipo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservacionEquipo query()
 */
	class ReservacionEquipo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $asignacion_aula_id
 * @property int $tema_id
 * @property \Illuminate\Support\Carbon $fecha_sesion
 * @property \Illuminate\Support\Carbon $hora_inicio_real
 * @property \Illuminate\Support\Carbon|null $hora_fin_real
 * @property string $tipo_actividad
 * @property string|null $descripcion
 * @property int $profesor_id
 * @property string|null $observaciones
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AsignacionAula $asignacionAula
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asistencia> $asistencias
 * @property-read int|null $asistencias_count
 * @property-read \App\Models\HistorialUsoAula|null $historial
 * @property-read \App\Models\User $profesor
 * @property-read \App\Models\Tema $tema
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereAsignacionAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereFechaSesion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereHoraFinReal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereHoraInicioReal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereProfesorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereTemaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereTipoActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SesionClase whereUpdatedAt($value)
 */
	class SesionClase extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $materia_id
 * @property int $numero_tema
 * @property string $nombre
 * @property string|null $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Materia $materia
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SesionClase> $sesiones
 * @property-read int|null $sesiones_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema whereMateriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema whereNumeroTema($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tema whereUpdatedAt($value)
 */
	class Tema extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $rol
 * @property string|null $no_ctrl
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $carrera_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asistencia> $asistencias
 * @property-read int|null $asistencias_count
 * @property-read \App\Models\Carrera|null $carrera
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Equipo> $equiposPersonales
 * @property-read int|null $equipos_personales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grupo> $grupos
 * @property-read int|null $grupos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grupo> $gruposComoProfesor
 * @property-read int|null $grupos_como_profesor_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HistorialUsoAula> $historialUsos
 * @property-read int|null $historial_usos_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservacionEquipo> $reservaciones
 * @property-read int|null $reservaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsoLibreEquipo> $usosLibres
 * @property-read int|null $usos_libres_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCarreraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNoCtrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $alumno_id
 * @property int $equipo_id
 * @property int $aula_id
 * @property \Illuminate\Support\Carbon $fecha_uso
 * @property \Illuminate\Support\Carbon $hora_inicio
 * @property \Illuminate\Support\Carbon|null $hora_fin
 * @property string $motivo
 * @property string|null $descripcion
 * @property int|null $autorizado_por
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $alumno
 * @property-read \App\Models\Aula $aula
 * @property-read \App\Models\User|null $autorizadoPor
 * @property-read \App\Models\Equipo $equipo
 * @property-read \App\Models\HistorialUsoAula|null $historial
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereAlumnoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereAutorizadoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereEquipoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereFechaUso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsoLibreEquipo whereUpdatedAt($value)
 */
	class UsoLibreEquipo extends \Eloquent {}
}

