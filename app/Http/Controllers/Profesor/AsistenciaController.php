<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Asistencia;
use App\Models\AsignacionAula;
use App\Models\HistorialUsoAula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    /**
     * Mostrar los grupos del profesor autenticado
     */
    public function index()
    {
        try {
            $grupos = Grupo::where('profesor_id', auth()->id())
                ->with(['materia', 'alumnos'])
                ->orderBy('periodo', 'desc')
                ->orderBy('clave_grupo', 'asc')
                ->get()
                ->map(function ($grupo) {
                    $grupo->cantidad_alumnos = $grupo->alumnos->count();
                    return $grupo;
                });

            return view('profesor.asistencias.index', compact('grupos'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los grupos: ' . $e->getMessage());
        }
    }

    /**
     * Vista para pasar lista de un grupo específico
     */
    public function pasarLista($grupoId)
    {
        try {
            // Verificar que el grupo pertenezca al profesor autenticado
            $grupo = Grupo::where('id', $grupoId)
                ->where('profesor_id', auth()->id())
                ->with(['alumnos.carrera', 'materia'])
                ->firstOrFail();

            // Fecha por defecto: hoy
            $fecha = date('Y-m-d');

            // Obtener el día de la semana en español
            $diasIngles = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $diasEspanol = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $diaHoy = date('l');
            $diaEspanol = str_replace($diasIngles, $diasEspanol, $diaHoy);

            // Obtener asignación de aula para el día actual
            $asignacionAula = AsignacionAula::where('grupo_id', $grupoId)
                ->where('dia_semana', $diaEspanol)
                ->where('activo', true)
                ->where(function ($query) use ($fecha) {
                    $query->whereNull('fecha_inicio_vigencia')
                        ->orWhere('fecha_inicio_vigencia', '<=', $fecha);
                })
                ->where(function ($query) use ($fecha) {
                    $query->whereNull('fecha_fin_vigencia')
                        ->orWhere('fecha_fin_vigencia', '>=', $fecha);
                })
                ->with('aula')
                ->first();

            // Verificar si ya se pasó lista hoy para este grupo
            $asistenciasExistentes = Asistencia::where('grupo_id', $grupoId)
                ->where('fecha', $fecha)
                ->get()
                ->keyBy('alumno_id');

            return view('profesor.asistencias.pasar-lista', compact(
                'grupo',
                'fecha',
                'asignacionAula',
                'asistenciasExistentes'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Guardar las asistencias del grupo
     */
    public function guardarAsistencias(Request $request, $grupoId)
    {
        try {
            // Validar datos
            $validated = $request->validate([
                'grupo_id' => 'required|exists:grupos,id',
                'fecha' => 'required|date|before_or_equal:today',
                'asistencias' => 'required|array|min:1',
                'asistencias.*.alumno_id' => 'required|exists:users,id',
                'asistencias.*.estado' => 'required|in:presente,ausente,retardo,justificado',
                'asistencias.*.observaciones' => 'nullable|string|max:500',
                'aula_id' => 'nullable|exists:aulas,id',
                'hora_entrada' => 'nullable|date_format:H:i',
                'hora_salida' => 'nullable|date_format:H:i',
            ], [
                'grupo_id.required' => 'El grupo es obligatorio.',
                'grupo_id.exists' => 'El grupo no existe.',
                'fecha.required' => 'La fecha es obligatoria.',
                'fecha.date' => 'La fecha debe ser válida.',
                'fecha.before_or_equal' => 'La fecha no puede ser futura.',
                'asistencias.required' => 'Debe registrar al menos una asistencia.',
                'asistencias.min' => 'Debe registrar al menos una asistencia.',
                'asistencias.*.alumno_id.required' => 'El ID del alumno es obligatorio.',
                'asistencias.*.alumno_id.exists' => 'El alumno no existe.',
                'asistencias.*.estado.required' => 'El estado es obligatorio.',
                'asistencias.*.estado.in' => 'El estado debe ser: presente, ausente, retardo o justificado.',
                'asistencias.*.observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres.',
                'aula_id.exists' => 'El aula no existe.',
                'hora_entrada.date_format' => 'La hora de entrada debe tener formato HH:MM.',
                'hora_salida.date_format' => 'La hora de salida debe tener formato HH:MM.',
            ]);

            // Verificar que el grupo pertenezca al profesor autenticado
            $grupo = Grupo::where('id', $grupoId)
                ->where('profesor_id', auth()->id())
                ->with('materia')
                ->firstOrFail();

            // Verificar que la fecha esté dentro del periodo de vigencia del grupo
            // Aquí asumimos que el grupo tiene año y periodo (ej: 2025, "Enero-Junio")
            $fechaAsistencia = \Carbon\Carbon::parse($validated['fecha']);
            $añoGrupo = $grupo->año;
            
            if ($fechaAsistencia->year != $añoGrupo) {
                return back()->withErrors(['fecha' => 'La fecha debe estar dentro del año del grupo (' . $añoGrupo . ').']);
            }

            DB::beginTransaction();

            $cantidadRegistros = 0;

            // Guardar cada asistencia
            foreach ($validated['asistencias'] as $asistenciaData) {
                Asistencia::updateOrCreate(
                    [
                        'grupo_id' => $grupoId,
                        'alumno_id' => $asistenciaData['alumno_id'],
                        'fecha' => $validated['fecha'],
                    ],
                    [
                        'estado' => $asistenciaData['estado'],
                        'observaciones' => $asistenciaData['observaciones'] ?? null,
                        'registrado_por' => auth()->id(),
                    ]
                );
                $cantidadRegistros++;
            }

            // Registrar en historial de uso de aula si se proporcionó
            if ($request->filled('aula_id') && $request->filled('hora_entrada') && $request->filled('hora_salida')) {
                HistorialUsoAula::create([
                    'aula_id' => $validated['aula_id'],
                    'usuario_id' => auth()->id(),
                    'grupo_id' => $grupoId,
                    'tipo_uso' => 'clase',
                    'fecha' => $validated['fecha'],
                    'hora_inicio' => $validated['hora_entrada'],
                    'hora_fin' => $validated['hora_salida'],
                    'descripcion' => 'Clase de ' . $grupo->materia->nombre,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('profesor.asistencias.historial', $grupoId)
                ->with('success', "Asistencias registradas exitosamente para {$cantidadRegistros} alumnos.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar las asistencias: ' . $e->getMessage());
        }
    }

    /**
     * Ver historial de asistencias del grupo
     */
    public function historial($grupoId)
    {
        try {
            // Verificar que el grupo pertenezca al profesor autenticado
            $grupo = Grupo::where('id', $grupoId)
                ->where('profesor_id', auth()->id())
                ->with(['materia', 'alumnos'])
                ->firstOrFail();

            // Cargar asistencias del grupo
            $asistencias = Asistencia::where('grupo_id', $grupoId)
                ->with('alumno')
                ->orderBy('fecha', 'desc')
                ->get();

            // Calcular estadísticas por alumno
            $estadisticas = [];
            $fechasClase = $asistencias->pluck('fecha')->unique()->count();

            foreach ($grupo->alumnos as $alumno) {
                $asistenciasAlumno = $asistencias->where('alumno_id', $alumno->id);
                
                $presentes = $asistenciasAlumno->where('estado', 'presente')->count();
                $ausentes = $asistenciasAlumno->where('estado', 'ausente')->count();
                $retardos = $asistenciasAlumno->where('estado', 'retardo')->count();
                $justificados = $asistenciasAlumno->where('estado', 'justificado')->count();
                $total = $asistenciasAlumno->count();
                
                $porcentaje = $total > 0 ? (($presentes + $retardos) / $total) * 100 : 0;

                $estadisticas[$alumno->id] = [
                    'alumno' => $alumno,
                    'total_clases' => $total,
                    'presentes' => $presentes,
                    'ausentes' => $ausentes,
                    'retardos' => $retardos,
                    'justificados' => $justificados,
                    'porcentaje_asistencia' => round($porcentaje, 2),
                ];
            }

            // Agrupar asistencias por fecha para mostrar en tabla
            $asistenciasPorFecha = $asistencias->groupBy('fecha')->sortKeysDesc();

            return view('profesor.asistencias.historial', compact(
                'grupo',
                'asistencias',
                'estadisticas',
                'asistenciasPorFecha',
                'fechasClase'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el historial: ' . $e->getMessage());
        }
    }

    /**
     * Ver reporte individual de un alumno
     */
    public function reporteAlumno($grupoId, $alumnoId)
    {
        try {
            // Verificar que el grupo pertenezca al profesor autenticado
            $grupo = Grupo::where('id', $grupoId)
                ->where('profesor_id', auth()->id())
                ->with('materia')
                ->firstOrFail();

            // Verificar que el alumno pertenezca al grupo
            $alumno = $grupo->alumnos()->where('users.id', $alumnoId)->firstOrFail();

            // Cargar todas las asistencias del alumno en este grupo
            $asistencias = Asistencia::where('grupo_id', $grupoId)
                ->where('alumno_id', $alumnoId)
                ->orderBy('fecha', 'desc')
                ->get();

            // Calcular estadísticas
            $presentes = $asistencias->where('estado', 'presente')->count();
            $ausentes = $asistencias->where('estado', 'ausente')->count();
            $retardos = $asistencias->where('estado', 'retardo')->count();
            $justificados = $asistencias->where('estado', 'justificado')->count();
            $total = $asistencias->count();
            
            $porcentajeAsistencia = $total > 0 ? (($presentes + $retardos) / $total) * 100 : 0;

            $estadisticas = [
                'total_clases' => $total,
                'presentes' => $presentes,
                'ausentes' => $ausentes,
                'retardos' => $retardos,
                'justificados' => $justificados,
                'porcentaje_asistencia' => round($porcentajeAsistencia, 2),
            ];

            return view('profesor.asistencias.reporte-alumno', compact(
                'grupo',
                'alumno',
                'asistencias',
                'estadisticas'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el reporte: ' . $e->getMessage());
        }
    }
}
