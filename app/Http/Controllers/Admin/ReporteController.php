<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Grupo;
use App\Models\Carrera;
use App\Models\Equipo;
use App\Models\Aula;
use App\Models\UsoLibreEquipo;
use App\Models\ReservacionEquipo;
use App\Models\HistorialUsoAula;
use App\Models\AsignacionAula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Reporte de Asistencias
     */
    public function asistencias(Request $request)
    {
        $carreras = Carrera::where('activo', true)->get();
        $grupos = Grupo::with(['materia', 'profesor'])->where('activo', true)->get();

        // Filtros
        $carreraId = $request->input('carrera_id');
        $grupoId = $request->input('grupo_id');
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        // Consulta base de asistencias
        $query = Asistencia::with(['alumno', 'sesionClase.asignacionAula.grupo.materia'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin]);

        if ($grupoId) {
            $query->whereHas('sesionClase.asignacionAula', function($q) use ($grupoId) {
                $q->where('grupo_id', $grupoId);
            });
        }

        if ($carreraId) {
            $query->whereHas('alumno', function($q) use ($carreraId) {
                $q->where('carrera_id', $carreraId);
            });
        }

        $asistencias = $query->latest('fecha')->paginate(20);

        // Estadísticas generales
        $estadisticas = [
            'total_asistencias' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->where('estado', 'presente')->count(),
            'total_faltas' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->where('estado', 'falta')->count(),
            'total_retardos' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->where('estado', 'retardo')->count(),
            'porcentaje_asistencia' => 0,
        ];

        $totalRegistros = $estadisticas['total_asistencias'] + $estadisticas['total_faltas'] + $estadisticas['total_retardos'];
        if ($totalRegistros > 0) {
            $estadisticas['porcentaje_asistencia'] = round(($estadisticas['total_asistencias'] / $totalRegistros) * 100, 2);
        }

        // Asistencias por grupo - corrección: grupo_id está en asignacion_aula
        $asistenciasPorGrupo = Asistencia::select('asignaciones_aula.grupo_id', DB::raw('COUNT(*) as total'))
            ->join('sesiones_clase', 'asistencias.sesion_clase_id', '=', 'sesiones_clase.id')
            ->join('asignaciones_aula', 'sesiones_clase.asignacion_aula_id', '=', 'asignaciones_aula.id')
            ->whereBetween('asistencias.fecha', [$fechaInicio, $fechaFin])
            ->where('asistencias.estado', 'presente')
            ->groupBy('asignaciones_aula.grupo_id')
            ->get()
            ->map(function($item) {
                $grupo = Grupo::with('materia')->find($item->grupo_id);
                $item->grupo = $grupo;
                return $item;
            });

        return view('admin.reportes.asistencias', compact(
            'asistencias',
            'carreras',
            'grupos',
            'estadisticas',
            'asistenciasPorGrupo',
            'carreraId',
            'grupoId',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Reporte de Uso de Equipos
     */
    public function equipos(Request $request)
    {
        try {
            $aulas = Aula::where('activo', true)->get();

            // Filtros
            $aulaId = $request->input('aula_id');
            $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
            $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

            // Estadísticas de equipos
            $estadisticas = [
                'total_equipos' => Equipo::count(),
                'equipos_operativos' => Equipo::where('estado', 'operativo')->count(),
                'equipos_mantenimiento' => Equipo::where('estado', 'en_mantenimiento')->count(),
                'equipos_dañados' => Equipo::where('estado', 'dañado')->count(),
                'equipos_baja' => Equipo::where('estado', 'dado_de_baja')->count(),
            ];

            // Uso libre de equipos
            $usoLibreQuery = UsoLibreEquipo::with(['equipo.aula', 'alumno'])
                ->whereBetween('fecha_uso', [$fechaInicio, $fechaFin]);

        if ($aulaId) {
            $usoLibreQuery->whereHas('equipo', function($q) use ($aulaId) {
                $q->where('aula_id', $aulaId);
            });
        }

        $usosLibres = $usoLibreQuery->latest('fecha_uso')->paginate(15);

        // Reservaciones de equipos
        $reservacionesQuery = ReservacionEquipo::with(['equipo.aula', 'alumno', 'grupo.materia'])
            ->whereBetween('fecha_reservacion', [$fechaInicio, $fechaFin]);

        if ($aulaId) {
            $reservacionesQuery->whereHas('equipo', function($q) use ($aulaId) {
                $q->where('aula_id', $aulaId);
            });
        }

        $reservaciones = $reservacionesQuery->latest('fecha_reservacion')->paginate(15);

        // Equipos más usados
        $equiposMasUsados = Equipo::with('aula')
            ->select('equipos.id', 'equipos.codigo_inventario', 'equipos.tipo', 'equipos.marca', 'equipos.modelo', 'equipos.estado', 'equipos.aula_id', DB::raw('COUNT(uso_libre_equipos.id) as total_usos'))
            ->leftJoin('uso_libre_equipos', function($join) use ($fechaInicio, $fechaFin) {
                $join->on('equipos.id', '=', 'uso_libre_equipos.equipo_id')
                     ->whereBetween('uso_libre_equipos.fecha_uso', [$fechaInicio, $fechaFin]);
            })
            ->groupBy('equipos.id', 'equipos.codigo_inventario', 'equipos.tipo', 'equipos.marca', 'equipos.modelo', 'equipos.estado', 'equipos.aula_id')
            ->orderByDesc('total_usos')
            ->limit(10)
            ->get();

            return view('admin.reportes.equipos', compact(
                'estadisticas',
                'usosLibres',
                'reservaciones',
                'equiposMasUsados',
                'aulas',
                'aulaId',
                'fechaInicio',
                'fechaFin'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Reporte de Ocupación de Aulas
     */
    public function aulas(Request $request)
    {
        $aulas = Aula::where('activo', true)->get();
        $edificios = Aula::select('edificio')->distinct()->pluck('edificio');

        // Filtros
        $aulaId = $request->input('aula_id');
        $edificio = $request->input('edificio');
        $diaSemana = $request->input('dia_semana');

        // Estadísticas de aulas
        $estadisticas = [
            'total_aulas' => Aula::where('activo', true)->count(),
            'total_edificios' => Aula::select('edificio')->distinct()->count(),
            'capacidad_total_alumnos' => Aula::where('activo', true)->sum('capacidad_alumnos'),
            'capacidad_total_equipos' => Aula::where('activo', true)->sum('capacidad_equipos'),
        ];

        // Asignaciones de aulas
        $asignacionesQuery = AsignacionAula::with(['aula', 'grupo.materia', 'grupo.profesor'])
            ->where('activo', true)
            ->where('fecha_inicio_vigencia', '<=', now())
            ->where('fecha_fin_vigencia', '>=', now());

        if ($aulaId) {
            $asignacionesQuery->where('aula_id', $aulaId);
        }

        if ($edificio) {
            $asignacionesQuery->whereHas('aula', function($q) use ($edificio) {
                $q->where('edificio', $edificio);
            });
        }

        if ($diaSemana) {
            $asignacionesQuery->where('dia_semana', $diaSemana);
        }

        $asignaciones = $asignacionesQuery->orderBy('dia_semana')->orderBy('hora_inicio')->get();

        // Ocupación por día de la semana
        $ocupacionPorDia = AsignacionAula::select('dia_semana', DB::raw('COUNT(*) as total'))
            ->where('activo', true)
            ->where('fecha_inicio_vigencia', '<=', now())
            ->where('fecha_fin_vigencia', '>=', now())
            ->groupBy('dia_semana')
            ->get()
            ->keyBy('dia_semana');

        // Aulas más ocupadas
        $aulasMasOcupadas = Aula::select('aulas.id', 'aulas.nombre', 'aulas.edificio', 'aulas.capacidad_alumnos', DB::raw('COUNT(asignaciones_aula.id) as total_asignaciones'))
            ->leftJoin('asignaciones_aula', function($join) {
                $join->on('aulas.id', '=', 'asignaciones_aula.aula_id')
                     ->where('asignaciones_aula.activo', '=', true);
            })
            ->groupBy('aulas.id', 'aulas.nombre', 'aulas.edificio', 'aulas.capacidad_alumnos')
            ->orderByDesc('total_asignaciones')
            ->limit(10)
            ->get();

        // Horarios disponibles por aula
        $horariosDisponibles = [];
        foreach ($aulas as $aula) {
            $horariosDisponibles[$aula->id] = $this->calcularHorariosDisponibles($aula->id);
        }

        return view('admin.reportes.aulas', compact(
            'estadisticas',
            'asignaciones',
            'ocupacionPorDia',
            'aulasMasOcupadas',
            'horariosDisponibles',
            'aulas',
            'edificios',
            'aulaId',
            'edificio',
            'diaSemana'
        ));
    }

    /**
     * Calcular horarios disponibles de un aula
     */
    private function calcularHorariosDisponibles($aulaId)
    {
        $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        $disponibles = 0;

        foreach ($diasSemana as $dia) {
            $asignaciones = AsignacionAula::where('aula_id', $aulaId)
                ->where('dia_semana', $dia)
                ->where('activo', true)
                ->get();

            // Calcular horas ocupadas (de 7am a 9pm = 14 horas)
            $horasOcupadas = 0;
            foreach ($asignaciones as $asignacion) {
                $inicio = Carbon::parse($asignacion->hora_inicio);
                $fin = Carbon::parse($asignacion->hora_fin);
                $horasOcupadas += $inicio->diffInHours($fin);
            }

            $disponibles += (14 - $horasOcupadas);
        }

        return $disponibles;
    }
}
