<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MateriaController extends Controller
{
    /**
     * Display a listing of the materias.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Materia::query()->with('carrera')->withCount('grupos');

        // Búsqueda por nombre o clave
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('clave', 'like', "%{$search}%");
            });
        }

        // Ordenar por nombre
        $query->orderBy('nombre');

        // Paginación
        $materias = $query->paginate(15)->withQueryString();

        // Estadísticas
        $stats = [
            'total' => Materia::count(),
            'con_grupos' => Materia::has('grupos')->count(),
            'sin_grupos' => Materia::doesntHave('grupos')->count(),
        ];

        return view('admin.materias.index', compact('materias', 'stats'));
    }

    /**
     * Show the form for creating a new materia.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $carreras = \App\Models\Carrera::orderBy('nombre')->get();
        return view('admin.materias.create', compact('carreras'));
    }

    /**
     * Store a newly created materia in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'clave' => 'required|string|max:20|unique:materias,clave',
            'carrera_id' => 'required|exists:carreras,id',
            'semestre' => 'required|integer|min:1|max:12',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre de la materia es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'clave.required' => 'La clave es obligatoria.',
            'clave.max' => 'La clave no puede tener más de 20 caracteres.',
            'clave.unique' => 'Esta clave ya está registrada.',
            'carrera_id.required' => 'La carrera es obligatoria.',
            'carrera_id.exists' => 'La carrera seleccionada no existe.',
            'semestre.required' => 'El semestre es obligatorio.',
            'semestre.integer' => 'El semestre debe ser un número.',
            'semestre.min' => 'El semestre debe ser al menos 1.',
            'semestre.max' => 'El semestre no puede ser mayor a 12.',
            'descripcion.max' => 'La descripción no puede tener más de 500 caracteres.',
        ]);

        Materia::create($validated);

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia creada exitosamente.');
    }

    /**
     * Show the form for editing the specified materia.
     * 
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\View\View
     */
    public function edit(Materia $materia)
    {
        $carreras = \App\Models\Carrera::orderBy('nombre')->get();
        return view('admin.materias.edit', compact('materia', 'carreras'));
    }

    /**
     * Update the specified materia in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'clave' => ['required', 'string', 'max:20', Rule::unique('materias', 'clave')->ignore($materia->id)],
            'carrera_id' => 'required|exists:carreras,id',
            'semestre' => 'required|integer|min:1|max:12',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre de la materia es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'clave.required' => 'La clave es obligatoria.',
            'clave.max' => 'La clave no puede tener más de 20 caracteres.',
            'clave.unique' => 'Esta clave ya está registrada.',
            'carrera_id.required' => 'La carrera es obligatoria.',
            'carrera_id.exists' => 'La carrera seleccionada no existe.',
            'semestre.required' => 'El semestre es obligatorio.',
            'semestre.integer' => 'El semestre debe ser un número.',
            'semestre.min' => 'El semestre debe ser al menos 1.',
            'semestre.max' => 'El semestre no puede ser mayor a 12.',
            'descripcion.max' => 'La descripción no puede tener más de 500 caracteres.',
        ]);

        $materia->update($validated);

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Remove the specified materia from storage.
     * 
     * @param  \App\Models\Materia  $materia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Materia $materia)
    {
        // Verificar si la materia tiene grupos asociados
        if ($materia->grupos()->count() > 0) {
            return redirect()->route('admin.materias.index')
                ->with('error', 'No se puede eliminar la materia porque tiene grupos asociados.');
        }

        $materia->delete();

        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia eliminada exitosamente.');
    }
}
