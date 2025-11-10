<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    /**
     * Display a listing of the grupos.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Grupo::query()
            ->with(['materia', 'profesor', 'alumnos'])
            ->withCount('alumnos');

        // Filtro por materia
        if ($request->filled('materia_id')) {
            $query->where('materia_id', $request->materia_id);
        }

        // Filtro por profesor
        if ($request->filled('profesor_id')) {
            $query->where('profesor_id', $request->profesor_id);
        }

        // Filtro por periodo
        if ($request->filled('periodo')) {
            $query->where('periodo', $request->periodo);
        }

        // Filtro por año
        if ($request->filled('año')) {
            $query->where('año', $request->año);
        }

        // Filtro por estado activo
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        // Búsqueda por clave_grupo
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('clave_grupo', 'like', "%{$search}%");
        }

        // Ordenar por clave_grupo
        $query->orderBy('clave_grupo');

        // Paginación
        $grupos = $query->paginate(15)->withQueryString();

        // Datos para los filtros
        $materias = Materia::orderBy('nombre')->get();
        $profesores = User::where('rol', 'profesor')->orderBy('name')->get();
        
        // Estadísticas
        $stats = [
            'total' => Grupo::count(),
            'activos' => Grupo::where('activo', true)->count(),
            'inactivos' => Grupo::where('activo', false)->count(),
            'con_alumnos' => Grupo::has('alumnos')->count(),
        ];

        return view('admin.grupos.index', compact('grupos', 'materias', 'profesores', 'stats'));
    }

    /**
     * Show the form for creating a new grupo.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $materias = Materia::orderBy('nombre')->get();
        $profesores = User::where('rol', 'profesor')->orderBy('name')->get();
        
        // Pre-llenar año con año actual
        $añoActual = date('Y');
        
        // Pre-seleccionar periodo según la fecha actual
        $mesActual = (int)date('n'); // 1-12
        if ($mesActual >= 1 && $mesActual <= 4) {
            $periodoActual = 'Ene-Abr';
        } elseif ($mesActual >= 5 && $mesActual <= 8) {
            $periodoActual = 'May-Ago';
        } else {
            $periodoActual = 'Sep-Dic';
        }
        
        return view('admin.grupos.create', compact('materias', 'profesores', 'añoActual', 'periodoActual'));
    }

    /**
     * Store a newly created grupo in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'profesor_id' => 'required|exists:users,id',
            'clave_grupo' => 'required|string|max:50|unique:grupos,clave_grupo',
            'periodo' => ['required', Rule::in(['Ene-Abr', 'May-Ago', 'Sep-Dic'])],
            'año' => 'required|integer|min:2020|max:2030',
            'activo' => 'boolean',
        ], [
            'materia_id.required' => 'La materia es obligatoria.',
            'materia_id.exists' => 'La materia seleccionada no existe.',
            'profesor_id.required' => 'El profesor es obligatorio.',
            'profesor_id.exists' => 'El profesor seleccionado no existe.',
            'clave_grupo.required' => 'La clave del grupo es obligatoria.',
            'clave_grupo.unique' => 'Esta clave de grupo ya está registrada.',
            'clave_grupo.max' => 'La clave no puede tener más de 50 caracteres.',
            'periodo.required' => 'El periodo es obligatorio.',
            'periodo.in' => 'El periodo seleccionado no es válido.',
            'año.required' => 'El año es obligatorio.',
            'año.integer' => 'El año debe ser un número.',
            'año.min' => 'El año debe ser al menos 2020.',
            'año.max' => 'El año no puede ser mayor a 2030.',
        ]);

        // Verificar que el profesor sea realmente profesor
        $profesor = User::find($validated['profesor_id']);
        if ($profesor->rol !== 'profesor') {
            return back()->withErrors(['profesor_id' => 'El usuario seleccionado no es un profesor.'])
                ->withInput();
        }

        // Si no se envió activo, establecer como false (checkbox desmarcado)
        $validated['activo'] = $request->has('activo');

        Grupo::create($validated);

        return redirect()->route('admin.grupos.index')
            ->with('success', 'Grupo creado exitosamente.');
    }

    /**
     * Show the form for editing the specified grupo.
     * 
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\View\View
     */
    public function edit(Grupo $grupo)
    {
        $materias = Materia::orderBy('nombre')->get();
        $profesores = User::where('rol', 'profesor')->orderBy('name')->get();
        $alumnosCount = $grupo->alumnos()->count();
        
        return view('admin.grupos.edit', compact('grupo', 'materias', 'profesores', 'alumnosCount'));
    }

    /**
     * Update the specified grupo in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Grupo $grupo)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'profesor_id' => 'required|exists:users,id',
            'clave_grupo' => ['required', 'string', 'max:50', Rule::unique('grupos', 'clave_grupo')->ignore($grupo->id)],
            'periodo' => ['required', Rule::in(['Ene-Abr', 'May-Ago', 'Sep-Dic'])],
            'año' => 'required|integer|min:2020|max:2030',
            'activo' => 'boolean',
        ], [
            'materia_id.required' => 'La materia es obligatoria.',
            'materia_id.exists' => 'La materia seleccionada no existe.',
            'profesor_id.required' => 'El profesor es obligatorio.',
            'profesor_id.exists' => 'El profesor seleccionado no existe.',
            'clave_grupo.required' => 'La clave del grupo es obligatoria.',
            'clave_grupo.unique' => 'Esta clave de grupo ya está registrada.',
            'clave_grupo.max' => 'La clave no puede tener más de 50 caracteres.',
            'periodo.required' => 'El periodo es obligatorio.',
            'periodo.in' => 'El periodo seleccionado no es válido.',
            'año.required' => 'El año es obligatorio.',
            'año.integer' => 'El año debe ser un número.',
            'año.min' => 'El año debe ser al menos 2020.',
            'año.max' => 'El año no puede ser mayor a 2030.',
        ]);

        // Verificar que el profesor sea realmente profesor
        $profesor = User::find($validated['profesor_id']);
        if ($profesor->rol !== 'profesor') {
            return back()->withErrors(['profesor_id' => 'El usuario seleccionado no es un profesor.'])
                ->withInput();
        }

        // Si no se envió activo, establecer como false (checkbox desmarcado)
        $validated['activo'] = $request->has('activo');

        $grupo->update($validated);

        return redirect()->route('admin.grupos.index')
            ->with('success', 'Grupo actualizado exitosamente.');
    }

    /**
     * Remove the specified grupo from storage.
     * 
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Grupo $grupo)
    {
        // Verificar si tiene alumnos inscritos
        if ($grupo->alumnos()->count() > 0) {
            return redirect()->route('admin.grupos.index')
                ->with('error', 'No se puede eliminar el grupo porque tiene alumnos inscritos.');
        }

        // Verificar si tiene asignaciones de aula
        if ($grupo->asignaciones()->count() > 0) {
            return redirect()->route('admin.grupos.index')
                ->with('error', 'No se puede eliminar el grupo porque tiene asignaciones de aula.');
        }

        $grupo->delete();

        return redirect()->route('admin.grupos.index')
            ->with('success', 'Grupo eliminado exitosamente.');
    }
}
