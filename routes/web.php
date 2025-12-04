<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Setting;
use App\Http\Controllers\Admin\AsignacionAulaController;
use App\Http\Controllers\Admin\AulaController;
use App\Http\Controllers\Admin\CarreraController;
use App\Http\Controllers\Admin\GrupoController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('inicio');
})->name('home');


Route::get('examen', [App\Http\Controllers\ExamenController::class, 'index'])->name('examen.index');
Route::get('examen/{id}', [App\Http\Controllers\ExamenController::class, 'mostrarCarreras'])->name('examen.show');
Route::get('examen/{id}/carrera/{carrera_id}', [App\Http\Controllers\ExamenController::class, 'mostrarMaterias'])->name('examen.materias');

// Route::get('dashboard', function () {
//     return view('index');
// })  ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::post('/api/settings/theme', function (Request $request) {
    $theme = $request->input('theme', 'light');
    if (in_array($theme, ['light', 'dark'])) {
        Setting::set('theme', $theme);
        return redirect()->back();
    }
    return redirect()->back()->withErrors(['theme' => 'Tema inválido']);
})->name('api.theme');

// Rutas de autenticación tradicional
Route::get('/login-simple', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login.simple');
Route::post('/login-simple', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/logout-simple', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.simple');

// Rutas para Profesores
Route::middleware(['auth', 'role:profesor'])->prefix('profesor')->name('profesor.')->group(function () {

    // Dashboard del profesor
    Route::get('/dashboard', [App\Http\Controllers\Profesor\AsistenciaController::class, 'index'])
        ->name('dashboard');

    // Asistencias
    Route::get('/grupos/{grupo}/pasar-lista', [App\Http\Controllers\Profesor\AsistenciaController::class, 'pasarLista'])
        ->name('asistencias.pasar-lista');

    Route::post('/grupos/{grupo}/guardar-asistencias', [App\Http\Controllers\Profesor\AsistenciaController::class, 'guardarAsistencias'])
        ->name('asistencias.guardar');

    Route::get('/grupos/{grupo}/historial', [App\Http\Controllers\Profesor\AsistenciaController::class, 'historial'])
        ->name('asistencias.historial');

    Route::get('/grupos/{grupo}/alumno/{alumno}/reporte', [App\Http\Controllers\Profesor\AsistenciaController::class, 'reporteAlumno'])
        ->name('asistencias.reporte-alumno');

    // Reservaciones de Equipos
    Route::resource('reservaciones', App\Http\Controllers\Profesor\ReservacionEquipoController::class);
    Route::get('/reservaciones/equipos-aula', [App\Http\Controllers\Profesor\ReservacionEquipoController::class, 'getEquiposPorAula'])
        ->name('reservaciones.equipos-aula');
});

// Rutas para Administradores
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD de Aulas
    Route::resource('aulas', AulaController::class);

    // CRUD de Equipos
    Route::resource('equipos', App\Http\Controllers\Admin\EquipoController::class);

    // CRUD de Asignaciones de Aula
    Route::resource('asignaciones', AsignacionAulaController::class);

    // Rutas adicionales para asignaciones
    Route::get('asignaciones/horario-semanal', [AsignacionAulaController::class, 'horarioSemanal'])
        ->name('asignaciones.horario-semanal');
    Route::get('asignaciones-grupo/editar', [AsignacionAulaController::class, 'editarGrupo'])
        ->name('asignaciones.editar-grupo');
    Route::put('asignaciones-grupo/actualizar', [AsignacionAulaController::class, 'actualizarGrupo'])
        ->name('asignaciones.actualizar-grupo');
    Route::delete('asignaciones-grupo/eliminar', [AsignacionAulaController::class, 'eliminarGrupo'])
        ->name('asignaciones.eliminar-grupo');

    // CRUD de Carreras
    Route::resource('carreras', CarreraController::class);

    // CRUD de Grupos
    Route::resource('grupos', GrupoController::class);

    // Rutas adicionales para gestión de alumnos en grupos
    Route::get('grupos/{grupo}/alumnos', [GrupoController::class, 'alumnos'])
        ->name('grupos.alumnos');
    Route::post('grupos/{grupo}/alumnos', [GrupoController::class, 'agregarAlumno'])
        ->name('grupos.alumnos.agregar');
    Route::delete('grupos/{grupo}/alumnos/{alumno}', [GrupoController::class, 'removerAlumno'])
        ->name('grupos.alumnos.remover');
    Route::patch('grupos/{grupo}/alumnos/{alumno}/toggle', [GrupoController::class, 'toggleAlumnoActivo'])
        ->name('grupos.alumnos.toggle');

    // CRUD de Materias
    Route::resource('materias', MateriaController::class);

    // CRUD de Usuarios
    Route::resource('users', UserController::class);

    // Ruta adicional para resetear contraseña
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->name('users.reset-password');

    // Reportes
    Route::get('reportes/asistencias', [App\Http\Controllers\Admin\ReporteController::class, 'asistencias'])
        ->name('reportes.asistencias');
    Route::get('reportes/equipos', [App\Http\Controllers\Admin\ReporteController::class, 'equipos'])
        ->name('reportes.equipos');
    Route::get('reportes/aulas', [App\Http\Controllers\Admin\ReporteController::class, 'aulas'])
        ->name('reportes.aulas');
});

require __DIR__ . '/auth.php';
