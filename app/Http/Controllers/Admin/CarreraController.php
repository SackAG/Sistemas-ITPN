<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarreraController extends Controller
{
    /**
     * Display a listing of the carreras.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Carrera::query()->withCount('usuarios');

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%{$search}%");
        }

        // Ordenar por nombre
        $query->orderBy('nombre');

        // Paginación
        $carreras = $query->paginate(15)->withQueryString();

        // Estadísticas
        $stats = [
            'total' => Carrera::count(),
            'con_usuarios' => Carrera::has('usuarios')->count(),
            'sin_usuarios' => Carrera::doesntHave('usuarios')->count(),
        ];

        return view('admin.carreras.index', compact('carreras', 'stats'));
    }

    /**
     * Show the form for creating a new carrera.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.carreras.create');
    }

    /**
     * Store a newly created carrera in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'clave' => 'required|string|max:20|unique:carreras,clave',
        ], [
            'nombre.required' => 'El nombre de la carrera es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'clave.required' => 'La clave es obligatoria.',
            'clave.max' => 'La clave no puede tener más de 20 caracteres.',
            'clave.unique' => 'Esta clave ya está registrada.',
        ]);

        Carrera::create($validated);

        return redirect()->route('admin.carreras.index')
            ->with('success', 'Carrera creada exitosamente.');
    }

    /**
     * Show the form for editing the specified carrera.
     * 
     * @param  \App\Models\Carrera  $carrera
     * @return \Illuminate\View\View
     */
    public function edit(Carrera $carrera)
    {
        return view('admin.carreras.edit', compact('carrera'));
    }

    /**
     * Update the specified carrera in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carrera  $carrera
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Carrera $carrera)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'clave' => ['required', 'string', 'max:20', Rule::unique('carreras', 'clave')->ignore($carrera->id)],
        ], [
            'nombre.required' => 'El nombre de la carrera es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'clave.required' => 'La clave es obligatoria.',
            'clave.max' => 'La clave no puede tener más de 20 caracteres.',
            'clave.unique' => 'Esta clave ya está registrada.',
        ]);

        $carrera->update($validated);

        return redirect()->route('admin.carreras.index')
            ->with('success', 'Carrera actualizada exitosamente.');
    }

    /**
     * Remove the specified carrera from storage.
     * 
     * @param  \App\Models\Carrera  $carrera
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Carrera $carrera)
    {
        // Verificar si la carrera tiene usuarios asignados
        if ($carrera->usuarios()->count() > 0) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se puede eliminar la carrera porque tiene usuarios asignados.');
        }

        $carrera->delete();

        return redirect()->route('admin.carreras.index')
            ->with('success', 'Carrera eliminada exitosamente.');
    }
}
