<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\ReservacionEquipo;
use App\Models\Equipo;
use App\Models\Aula;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservacionEquipoController extends Controller
{
    /**
     * Mostrar lista de reservaciones del profesor
     */
    public function index(Request $request)
    {
        try {
            $query = ReservacionEquipo::with(['equipo', 'aula', 'grupo'])
                ->where('profesor_id', auth()->id());

            // Filtro por estado
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            // Filtro por fecha
            if ($request->filled('fecha_desde')) {
                $query->where('fecha_reservacion', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->where('fecha_reservacion', '<=', $request->fecha_hasta);
            }

            // Filtro por equipo
            if ($request->filled('equipo_id')) {
                $query->where('equipo_id', $request->equipo_id);
            }

            // Ordenar por fecha más reciente
            $reservaciones = $query->orderBy('fecha_reservacion', 'desc')
                ->orderBy('hora_inicio', 'desc')
                ->paginate(15)
                ->withQueryString();

            // Estadísticas
            $estadisticas = [
                'pendientes' => ReservacionEquipo::where('profesor_id', auth()->id())
                    ->where('estado', 'pendiente')->count(),
                'aprobadas' => ReservacionEquipo::where('profesor_id', auth()->id())
                    ->where('estado', 'aprobada')->count(),
                'rechazadas' => ReservacionEquipo::where('profesor_id', auth()->id())
                    ->where('estado', 'rechazada')->count(),
                'total' => ReservacionEquipo::where('profesor_id', auth()->id())->count(),
            ];

            // Equipos para filtro
            $equipos = Equipo::where('activo', true)
                ->orderBy('nombre')
                ->get();

            return view('profesor.reservaciones.index', compact('reservaciones', 'estadisticas', 'equipos'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las reservaciones: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear reservación
     */
    public function create()
    {
        try {
            // Obtener grupos del profesor
            $grupos = Grupo::where('profesor_id', auth()->id())
                ->where('activo', true)
                ->with('materia')
                ->orderBy('clave_grupo')
                ->get();

            // Obtener equipos disponibles
            $equipos = Equipo::where('activo', true)
                ->where('estado', 'disponible')
                ->orderBy('nombre')
                ->get();

            // Obtener aulas disponibles
            $aulas = Aula::where('activo', true)
                ->orderBy('nombre')
                ->get();

            return view('profesor.reservaciones.create', compact('grupos', 'equipos', 'aulas'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nueva reservación
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'grupo_id' => 'required|exists:grupos,id',
                'equipo_id' => 'required|exists:equipos,id',
                'aula_id' => 'required|exists:aulas,id',
                'fecha_reservacion' => 'required|date|after_or_equal:today',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
                'motivo' => 'required|string|max:500',
            ], [
                'grupo_id.required' => 'Debes seleccionar un grupo.',
                'equipo_id.required' => 'Debes seleccionar un equipo.',
                'aula_id.required' => 'Debes seleccionar un aula.',
                'fecha_reservacion.required' => 'La fecha es obligatoria.',
                'fecha_reservacion.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
                'hora_inicio.required' => 'La hora de inicio es obligatoria.',
                'hora_fin.required' => 'La hora de fin es obligatoria.',
                'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
                'motivo.required' => 'Debes especificar el motivo de la reservación.',
                'motivo.max' => 'El motivo no puede exceder 500 caracteres.',
            ]);

            // Verificar que el grupo pertenezca al profesor
            $grupo = Grupo::where('id', $validated['grupo_id'])
                ->where('profesor_id', auth()->id())
                ->firstOrFail();

            // Verificar disponibilidad del equipo
            $conflicto = ReservacionEquipo::where('equipo_id', $validated['equipo_id'])
                ->where('fecha_reservacion', $validated['fecha_reservacion'])
                ->where('estado', '!=', 'rechazada')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('hora_inicio', [$validated['hora_inicio'], $validated['hora_fin']])
                        ->orWhereBetween('hora_fin', [$validated['hora_inicio'], $validated['hora_fin']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('hora_inicio', '<=', $validated['hora_inicio'])
                                ->where('hora_fin', '>=', $validated['hora_fin']);
                        });
                })
                ->exists();

            if ($conflicto) {
                return back()
                    ->withInput()
                    ->with('error', 'El equipo ya está reservado para ese horario. Por favor elige otro horario o equipo.');
            }

            // Crear reservación
            $validated['profesor_id'] = auth()->id();
            $validated['estado'] = 'pendiente';

            ReservacionEquipo::create($validated);

            return redirect()
                ->route('profesor.reservaciones.index')
                ->with('success', 'Reservación creada exitosamente. Está pendiente de aprobación.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear la reservación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de reservación
     */
    public function show($id)
    {
        try {
            $reservacion = ReservacionEquipo::with(['equipo', 'aula', 'grupo.materia', 'profesor'])
                ->where('profesor_id', auth()->id())
                ->findOrFail($id);

            return view('profesor.reservaciones.show', compact('reservacion'));
        } catch (\Exception $e) {
            return back()->with('error', 'Reservación no encontrada.');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $reservacion = ReservacionEquipo::where('profesor_id', auth()->id())
                ->where('estado', 'pendiente')
                ->findOrFail($id);

            // Solo se pueden editar reservaciones pendientes
            if ($reservacion->estado !== 'pendiente') {
                return back()->with('error', 'Solo puedes editar reservaciones pendientes.');
            }

            // Obtener datos para el formulario
            $grupos = Grupo::where('profesor_id', auth()->id())
                ->where('activo', true)
                ->with('materia')
                ->orderBy('clave_grupo')
                ->get();

            $equipos = Equipo::where('activo', true)
                ->where('estado', 'disponible')
                ->orderBy('nombre')
                ->get();

            $aulas = Aula::where('activo', true)
                ->orderBy('nombre')
                ->get();

            return view('profesor.reservaciones.edit', compact('reservacion', 'grupos', 'equipos', 'aulas'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar la reservación: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar reservación
     */
    public function update(Request $request, $id)
    {
        try {
            $reservacion = ReservacionEquipo::where('profesor_id', auth()->id())
                ->where('estado', 'pendiente')
                ->findOrFail($id);

            $validated = $request->validate([
                'grupo_id' => 'required|exists:grupos,id',
                'equipo_id' => 'required|exists:equipos,id',
                'aula_id' => 'required|exists:aulas,id',
                'fecha_reservacion' => 'required|date|after_or_equal:today',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
                'motivo' => 'required|string|max:500',
            ]);

            // Verificar disponibilidad (excluyendo la reservación actual)
            $conflicto = ReservacionEquipo::where('equipo_id', $validated['equipo_id'])
                ->where('fecha_reservacion', $validated['fecha_reservacion'])
                ->where('estado', '!=', 'rechazada')
                ->where('id', '!=', $id)
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('hora_inicio', [$validated['hora_inicio'], $validated['hora_fin']])
                        ->orWhereBetween('hora_fin', [$validated['hora_inicio'], $validated['hora_fin']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('hora_inicio', '<=', $validated['hora_inicio'])
                                ->where('hora_fin', '>=', $validated['hora_fin']);
                        });
                })
                ->exists();

            if ($conflicto) {
                return back()
                    ->withInput()
                    ->with('error', 'El equipo ya está reservado para ese horario.');
            }

            $reservacion->update($validated);

            return redirect()
                ->route('profesor.reservaciones.index')
                ->with('success', 'Reservación actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la reservación: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar/eliminar reservación
     */
    public function destroy($id)
    {
        try {
            $reservacion = ReservacionEquipo::where('profesor_id', auth()->id())
                ->findOrFail($id);

            // Solo se pueden cancelar reservaciones pendientes o aprobadas que aún no han pasado
            if ($reservacion->estado === 'rechazada') {
                return back()->with('error', 'No puedes cancelar una reservación rechazada.');
            }

            if ($reservacion->fecha_reservacion < now()->toDateString()) {
                return back()->with('error', 'No puedes cancelar una reservación pasada.');
            }

            $reservacion->update(['estado' => 'cancelada']);

            return redirect()
                ->route('profesor.reservaciones.index')
                ->with('success', 'Reservación cancelada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cancelar la reservación: ' . $e->getMessage());
        }
    }

    /**
     * Obtener equipos disponibles por aula (AJAX)
     */
    public function getEquiposPorAula(Request $request)
    {
        try {
            $aulaId = $request->aula_id;
            $fecha = $request->fecha;
            $horaInicio = $request->hora_inicio;
            $horaFin = $request->hora_fin;

            $equipos = Equipo::where('aula_id', $aulaId)
                ->where('activo', true)
                ->where('estado', 'disponible')
                ->whereDoesntHave('reservaciones', function ($query) use ($fecha, $horaInicio, $horaFin) {
                    $query->where('fecha_reservacion', $fecha)
                        ->where('estado', '!=', 'rechazada')
                        ->where(function ($q) use ($horaInicio, $horaFin) {
                            $q->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                                ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                                ->orWhere(function ($subq) use ($horaInicio, $horaFin) {
                                    $subq->where('hora_inicio', '<=', $horaInicio)
                                        ->where('hora_fin', '>=', $horaFin);
                                });
                        });
                })
                ->get(['id', 'nombre', 'codigo_inventario', 'tipo']);

            return response()->json($equipos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
