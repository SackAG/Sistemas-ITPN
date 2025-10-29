<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Http\Requests\StoreAulaRequest;
use App\Http\Requests\UpdateAulaRequest;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    /**
     * Muestra la lista de todas las aulas.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todas las aulas con paginación de 15 por página
        $aulas = Aula::orderBy('nombre')->paginate(15);
        
        return view('admin.aulas.index', compact('aulas'));
    }

    /**
     * Muestra el formulario para crear una nueva aula.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.aulas.create');
    }

    /**
     * Guarda una nueva aula en la base de datos.
     * 
     * @param  \App\Http\Requests\StoreAulaRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAulaRequest $request)
    {
        // Crear el aula con los datos validados
        Aula::create($request->validated());
        
        // Redirigir al listado con mensaje de éxito
        return redirect()->route('admin.aulas.index')
            ->with('success', 'Aula creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar un aula existente.
     * 
     * @param  \App\Models\Aula  $aula
     * @return \Illuminate\View\View
     */
    public function edit(Aula $aula)
    {
        return view('admin.aulas.edit', compact('aula'));
    }

    /**
     * Actualiza un aula existente en la base de datos.
     * 
     * @param  \App\Http\Requests\UpdateAulaRequest  $request
     * @param  \App\Models\Aula  $aula
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAulaRequest $request, Aula $aula)
    {
        // Actualizar el aula con los datos validados
        $aula->update($request->validated());
        
        // Redirigir al listado con mensaje de éxito
        return redirect()->route('admin.aulas.index')
            ->with('success', 'Aula actualizada exitosamente.');
    }

    /**
     * Elimina un aula de la base de datos.
     * 
     * Verifica que el aula no tenga equipos ni asignaciones antes de eliminar.
     * 
     * @param  \App\Models\Aula  $aula
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Aula $aula)
    {
        // Verificar si el aula tiene equipos
        if ($aula->equipos()->count() > 0) {
            return redirect()->route('admin.aulas.index')
                ->with('error', 'No se puede eliminar el aula porque tiene equipos asignados.');
        }
        
        // Verificar si el aula tiene asignaciones
        if ($aula->asignaciones()->count() > 0) {
            return redirect()->route('admin.aulas.index')
                ->with('error', 'No se puede eliminar el aula porque tiene asignaciones de horario.');
        }
        
        // Eliminar el aula
        $aula->delete();
        
        // Redirigir al listado con mensaje de éxito
        return redirect()->route('admin.aulas.index')
            ->with('success', 'Aula eliminada exitosamente.');
    }
}
