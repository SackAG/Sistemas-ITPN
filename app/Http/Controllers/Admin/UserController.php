<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query()->with('carrera');

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_ctrl', 'like', "%{$search}%");
            });
        }

        // Ordenar por nombre
        $query->orderBy('name');

        // Paginación
        $users = $query->paginate(15)->withQueryString();

        // Estadísticas por rol
        $stats = [
            'total' => User::count(),
            'admins' => User::where('rol', 'admin')->count(),
            'profesores' => User::where('rol', 'profesor')->count(),
            'alumnos' => User::where('rol', 'alumno')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $carreras = Carrera::orderBy('nombre')->get();
        return view('admin.users.create', compact('carreras'));
    }

    /**
     * Store a newly created user in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol' => ['required', Rule::in(['admin', 'profesor', 'alumno'])],
        ];

        // Validaciones condicionales según el rol
        if ($request->rol === 'alumno') {
            // Si es alumno: numero_control y carrera obligatorios
            $rules['no_ctrl'] = 'required|string|max:20|unique:users,no_ctrl';
            $rules['carrera_id'] = 'required|exists:carreras,id';
        } elseif ($request->rol === 'profesor') {
            // Si es profesor: carrera opcional (representa su departamento)
            $rules['carrera_id'] = 'nullable|exists:carreras,id';
            $rules['no_ctrl'] = 'nullable|string|max:20|unique:users,no_ctrl';
        } else {
            // Si es admin: sin carrera ni número de control
            $rules['carrera_id'] = 'nullable';
            $rules['no_ctrl'] = 'nullable';
        }

        $validated = $request->validate($rules, [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'no_ctrl.required' => 'El número de control es obligatorio para alumnos.',
            'no_ctrl.unique' => 'Este número de control ya está registrado.',
            'carrera_id.required' => 'La carrera es obligatoria para alumnos.',
            'carrera_id.exists' => 'La carrera seleccionada no existe.',
        ]);

        // Crear el usuario
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => $validated['rol'],
            'no_ctrl' => $validated['no_ctrl'] ?? null,
            'carrera_id' => $validated['carrera_id'] ?? null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing the specified user.
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // return $user;
        $carreras = Carrera::orderBy('nombre')->get();
        return view('admin.users.edit', compact('user', 'carreras'));
    }

    /**
     * Update the specified user in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'rol' => ['required', Rule::in(['admin', 'profesor', 'alumno'])],
        ];

        // Validaciones condicionales según el rol
        if ($request->rol === 'alumno') {
            // Si es alumno: numero_control y carrera obligatorios
            $rules['no_ctrl'] = ['required', 'string', 'max:20', Rule::unique('users', 'no_ctrl')->ignore($user->id)];
            $rules['carrera_id'] = 'required|exists:carreras,id';
        } elseif ($request->rol === 'profesor') {
            // Si es profesor: carrera opcional (representa su departamento)
            $rules['carrera_id'] = 'nullable|exists:carreras,id';
            $rules['no_ctrl'] = ['nullable', 'string', 'max:20', Rule::unique('users', 'no_ctrl')->ignore($user->id)];
        } else {
            // Si es admin: sin carrera ni número de control
            $rules['carrera_id'] = 'nullable';
            $rules['no_ctrl'] = ['nullable', 'string', 'max:20', Rule::unique('users', 'no_ctrl')->ignore($user->id)];
        }

        $validated = $request->validate($rules, [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'no_ctrl.required' => 'El número de control es obligatorio para alumnos.',
            'no_ctrl.unique' => 'Este número de control ya está registrado.',
            'carrera_id.required' => 'La carrera es obligatoria para alumnos.',
            'carrera_id.exists' => 'La carrera seleccionada no existe.',
        ]);

        // Actualizar datos
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->rol = $validated['rol'];
        $user->no_ctrl = $validated['no_ctrl'] ?? null;
        $user->carrera_id = $validated['carrera_id'] ?? null;

        // Solo actualizar contraseña si se proporciona
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user from storage.
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // No permitir eliminar el propio usuario
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Verificar si el usuario tiene relaciones que impidan su eliminación
        if ($user->rol === 'profesor' && $user->gruposComoProfesor()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No se puede eliminar el profesor porque tiene grupos asignados.');
        }

        if ($user->rol === 'alumno' && $user->grupos()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No se puede eliminar el alumno porque está inscrito en grupos.');
        }

        // Eliminar el usuario
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Reset the password of the specified user.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Actualizar la contraseña
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Contraseña de {$user->name} restablecida exitosamente.");
    }
}
