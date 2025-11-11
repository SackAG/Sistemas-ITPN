<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsignacionAula;
use App\Models\Aula;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsignacionAulaController extends Controller
{
    /**
     * Display a listing of the asignaciones.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = AsignacionAula::query()
            ->with(['aula', 'grupo.materia', 'grupo.profesor']);

        // Filtro por aula
        if ($request->filled('aula_id')) {
            $query->where('aula_id', $request->aula_id);
        }

        // Filtro por grupo
        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }

        // Filtro por día de la semana
        if ($request->filled('dia_semana')) {
            $query->where('dia_semana', $request->dia_semana);
        }

        // Filtro por estado activo
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        // Ordenar por día y hora
        $asignaciones = $query->orderByRaw("
            FIELD(dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado')
        ")->orderBy('hora_inicio')->paginate(15);

        // Datos para los filtros
        $aulas = Aula::orderBy('nombre')->get();
        $grupos = Grupo::with(['materia', 'profesor'])
            ->orderBy('clave_grupo')
            ->get();
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('admin.asignaciones.index', compact(
            'asignaciones',
            'aulas',
            'grupos',
            'diasSemana'
        ));
    }

    /**
     * Show the form for creating a new asignacion.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Obtener periodo actual (asumiendo año actual)
        $añoActual = date('Y');
        
        // Calcular fechas de vigencia por defecto (periodo actual)
        // Asumiendo periodo Ene-Jun y Jul-Dic
        $mesActual = date('n');
        if ($mesActual <= 6) {
            // Periodo Ene-Jun
            $fechaInicio = $añoActual . '-01-01';
            $fechaFin = $añoActual . '-06-30';
        } else {
            // Periodo Jul-Dic
            $fechaInicio = $añoActual . '-07-01';
            $fechaFin = $añoActual . '-12-31';
        }

        $aulas = Aula::orderBy('nombre')->get();
        $grupos = Grupo::with(['materia', 'profesor'])
            ->orderBy('clave_grupo')
            ->get();
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('admin.asignaciones.create', compact(
            'aulas',
            'grupos',
            'diasSemana',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Store a newly created asignacion in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación básica
        $validated = $request->validate([
            'aula_id' => 'required|exists:aulas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'fecha_inicio_vigencia' => 'required|date',
            'fecha_fin_vigencia' => 'required|date|after_or_equal:fecha_inicio_vigencia',
            'activo' => 'nullable|boolean',
        ], [
            'aula_id.required' => 'El aula es obligatoria.',
            'aula_id.exists' => 'El aula seleccionada no existe.',
            'grupo_id.required' => 'El grupo es obligatorio.',
            'grupo_id.exists' => 'El grupo seleccionado no existe.',
            'dia_semana.required' => 'El día de la semana es obligatorio.',
            'dia_semana.in' => 'El día de la semana debe ser: Lunes, Martes, Miércoles, Jueves, Viernes o Sábado.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'fecha_inicio_vigencia.required' => 'La fecha de inicio de vigencia es obligatoria.',
            'fecha_inicio_vigencia.date' => 'La fecha de inicio de vigencia debe ser una fecha válida.',
            'fecha_fin_vigencia.required' => 'La fecha de fin de vigencia es obligatoria.',
            'fecha_fin_vigencia.date' => 'La fecha de fin de vigencia debe ser una fecha válida.',
            'fecha_fin_vigencia.after_or_equal' => 'La fecha de fin de vigencia debe ser igual o posterior a la fecha de inicio.',
        ]);

        // Validación de conflictos de horario
        $conflicto = $this->verificarConflictoHorario(
            $request->aula_id,
            $request->dia_semana,
            $request->hora_inicio,
            $request->hora_fin
        );

        if ($conflicto) {
            $aula = Aula::find($request->aula_id);
            $grupo = Grupo::with('materia')->find($conflicto->grupo_id);
            
            return back()->withInput()->withErrors([
                'hora_inicio' => "El aula {$aula->nombre} ya está ocupada el {$request->dia_semana} de {$conflicto->hora_inicio} a {$conflicto->hora_fin} por el grupo {$grupo->clave_grupo}."
            ]);
        }

        // Crear la asignación
        $validated['activo'] = $request->has('activo');

        AsignacionAula::create($validated);

        return redirect()->route('admin.asignaciones.index')
            ->with('success', 'Asignación de aula creada exitosamente.');
    }

    /**
     * Show the form for editing the specified asignacion.
     * 
     * @param  \App\Models\AsignacionAula  $asignacione
     * @return \Illuminate\View\View
     */
    public function edit(AsignacionAula $asignacione)
    {
        $aulas = Aula::orderBy('nombre')->get();
        $grupos = Grupo::with(['materia', 'profesor'])
            ->orderBy('clave_grupo')
            ->get();
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return view('admin.asignaciones.edit', compact(
            'asignacione',
            'aulas',
            'grupos',
            'diasSemana'
        ));
    }

    /**
     * Update the specified asignacion in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AsignacionAula  $asignacione
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, AsignacionAula $asignacione)
    {
        // Validación básica
        $validated = $request->validate([
            'aula_id' => 'required|exists:aulas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'fecha_inicio_vigencia' => 'required|date',
            'fecha_fin_vigencia' => 'required|date|after_or_equal:fecha_inicio_vigencia',
            'activo' => 'nullable|boolean',
        ], [
            'aula_id.required' => 'El aula es obligatoria.',
            'aula_id.exists' => 'El aula seleccionada no existe.',
            'grupo_id.required' => 'El grupo es obligatorio.',
            'grupo_id.exists' => 'El grupo seleccionado no existe.',
            'dia_semana.required' => 'El día de la semana es obligatorio.',
            'dia_semana.in' => 'El día de la semana debe ser: Lunes, Martes, Miércoles, Jueves, Viernes o Sábado.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:MM.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.date_format' => 'La hora de fin debe tener el formato HH:MM.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'fecha_inicio_vigencia.required' => 'La fecha de inicio de vigencia es obligatoria.',
            'fecha_inicio_vigencia.date' => 'La fecha de inicio de vigencia debe ser una fecha válida.',
            'fecha_fin_vigencia.required' => 'La fecha de fin de vigencia es obligatoria.',
            'fecha_fin_vigencia.date' => 'La fecha de fin de vigencia debe ser una fecha válida.',
            'fecha_fin_vigencia.after_or_equal' => 'La fecha de fin de vigencia debe ser igual o posterior a la fecha de inicio.',
        ]);

        // Validación de conflictos de horario (ignorando la asignación actual)
        $conflicto = $this->verificarConflictoHorario(
            $request->aula_id,
            $request->dia_semana,
            $request->hora_inicio,
            $request->hora_fin,
            $asignacione->id
        );

        if ($conflicto) {
            $aula = Aula::find($request->aula_id);
            $grupo = Grupo::with('materia')->find($conflicto->grupo_id);
            
            return back()->withInput()->withErrors([
                'hora_inicio' => "El aula {$aula->nombre} ya está ocupada el {$request->dia_semana} de {$conflicto->hora_inicio} a {$conflicto->hora_fin} por el grupo {$grupo->clave_grupo}."
            ]);
        }

        // Actualizar la asignación
        $validated['activo'] = $request->has('activo');

        $asignacione->update($validated);

        return redirect()->route('admin.asignaciones.index')
            ->with('success', 'Asignación de aula actualizada exitosamente.');
    }

    /**
     * Remove the specified asignacion from storage.
     * 
     * @param  \App\Models\AsignacionAula  $asignacione
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AsignacionAula $asignacione)
    {
        // Verificar si tiene sesiones de clase registradas
        if ($asignacione->sesiones()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la asignación porque tiene sesiones de clase registradas.');
        }

        $asignacione->delete();

        return redirect()->route('admin.asignaciones.index')
            ->with('success', 'Asignación de aula eliminada exitosamente.');
    }

    /**
     * Verificar si existe un conflicto de horario en el aula.
     * 
     * @param  int  $aulaId
     * @param  string  $diaSemana
     * @param  string  $horaInicio
     * @param  string  $horaFin
     * @param  int|null  $ignorarId
     * @return \App\Models\AsignacionAula|null
     */
    private function verificarConflictoHorario($aulaId, $diaSemana, $horaInicio, $horaFin, $ignorarId = null)
    {
        $query = AsignacionAula::where('aula_id', $aulaId)
            ->where('dia_semana', $diaSemana)
            ->where(function($q) use ($horaInicio, $horaFin) {
                // Detectar traslape: (nueva_hora_inicio < existente_hora_fin) AND (nueva_hora_fin > existente_hora_inicio)
                $q->whereRaw("TIME(?) < TIME(hora_fin) AND TIME(?) > TIME(hora_inicio)", [$horaInicio, $horaFin]);
            });

        // Ignorar la asignación actual al editar
        if ($ignorarId) {
            $query->where('id', '!=', $ignorarId);
        }

        return $query->first();
    }
}
