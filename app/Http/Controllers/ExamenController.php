<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;

class ExamenController extends Controller
{
    public function index()
    { 
        return view('examen');
    }

    public function show($id)
    {
        return view('examenDos', compact('id'));
    }

    public function mostrarCarreras(Request $request, $id)
    {

        if (!$request->has('mostrar')) {
            return view('examenDos', compact('id'));
        }
        

        $carreras = Carrera::all();
        return view('examenDos', compact('id', 'carreras'));
    }

    public function mostrarMaterias($id, $carrera_id)
    {
        $carreras = Carrera::all();
        $carrera_seleccionada = Carrera::with('materias')->findOrFail($carrera_id);
        $materias = $carrera_seleccionada->materias;
        
        return view('examenDos', compact('id', 'carreras', 'carrera_seleccionada', 'materias'));
    }
}
