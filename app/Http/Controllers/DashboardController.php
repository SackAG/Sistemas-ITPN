<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Equipo;
use App\Models\Grupo;
use App\Models\User;
use App\Models\SesionClase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        switch ($user->rol) {
            case 'admin':
                return $this->dashboardAdmin();
                
            case 'profesor':
                return $this->dashboardProfesor($user);
                
            case 'alumno':
                return $this->dashboardAlumno($user);
                
            default:

                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Tu cuenta no tiene un rol válido.');
        }
    }

    /**
     * Dashboard para Administradores.
     * Muestra estadísticas generales del sistema.
     */
    private function dashboardAdmin()
    {
        // Obtener estadísticas
        $totalAulas = Aula::count();
        $totalEquipos = Equipo::where('estado', 'operativo')->count();
        $totalGrupos = Grupo::where('activo', true)->count();
        $totalUsuarios = User::count();

        // Contadores por rol
        $totalAdmins = User::where('rol', 'admin')->count();
        $totalProfesores = User::where('rol', 'profesor')->count();
        $totalAlumnos = User::where('rol', 'alumno')->count();

        return view('dashboard.admin', compact(
            'totalAulas',
            'totalEquipos',
            'totalGrupos',
            'totalUsuarios',
            'totalAdmins',
            'totalProfesores',
            'totalAlumnos'
        ));
    }

    /**
     * Dashboard para Profesores.
     * Muestra los grupos donde enseña y sus últimas sesiones.
     */
    private function dashboardProfesor($user)
    {
        // Obtener los grupos donde el profesor enseña
        // Eager loading: cargar también la materia de cada grupo
        $grupos = $user->gruposComoProfesor()
            ->with('materia')
            ->where('activo', true)
            ->get();

        // Obtener las últimas 5 sesiones de clase del profesor
        $sesiones = SesionClase::where('profesor_id', $user->id)
            ->with(['asignacionAula.grupo.materia', 'tema'])
            ->latest('fecha_sesion')
            ->take(5)
            ->get();

        return view('dashboard.profesor', compact('grupos', 'sesiones'));
    }

    /**
     * Dashboard para Alumnos.
     * Muestra los grupos donde está inscrito.
     */
    private function dashboardAlumno($user)
    {
        // Obtener los grupos donde el alumno está inscrito
        // Eager loading: cargar materia y profesor
        // withPivot: incluir datos de la tabla pivote (fecha_inscripcion, activo)
        $grupos = $user->grupos()
            ->with(['materia', 'profesor'])
            ->wherePivot('activo', true)
            ->get();

        return view('dashboard.alumno', compact('grupos'));
    }
}