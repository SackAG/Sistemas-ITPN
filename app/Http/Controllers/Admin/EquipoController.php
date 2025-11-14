<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Aula;
use App\Models\HistorialUsoAula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EquipoController extends Controller
{
    /**
     * Listar todos los equipos con filtros y estadísticas
     */
    public function index(Request $request)
    {
        try {
            // Estadísticas generales
            $totalEquipos = Equipo::count();
            $disponibles = Equipo::where('estado', 'disponible')->count();
            $enUso = Equipo::where('estado', 'en_uso')->count();
            $enMantenimiento = Equipo::where('estado', 'mantenimiento')->count();
            $dañados = Equipo::where('estado', 'dañado')->count();

            // Query base con eager loading
            $query = Equipo::with('aula');

            // Filtro por tipo
            if ($request->filled('tipo')) {
                $query->where('tipo', $request->tipo);
            }

            // Filtro por estado
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            // Filtro por aula
            if ($request->filled('aula_id')) {
                $query->where('aula_id', $request->aula_id);
            }

            // Búsqueda general
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('modelo', 'like', "%{$search}%")
                      ->orWhere('numero_serie', 'like', "%{$search}%")
                      ->orWhere('codigo_inventario', 'like', "%{$search}%");
                });
            }

            // Ordenamiento
            $orderBy = $request->get('order_by', 'nombre');
            $orderDirection = $request->get('order_direction', 'asc');
            
            if (in_array($orderBy, ['nombre', 'fecha_adquisicion', 'created_at'])) {
                $query->orderBy($orderBy, $orderDirection);
            } else {
                $query->orderBy('nombre', 'asc');
            }

            // Paginación
            $equipos = $query->paginate(20)->withQueryString();

            // Cargar aulas para filtros
            $aulas = Aula::where('activo', true)->orderBy('nombre')->get();

            return view('admin.equipos.index', compact(
                'equipos',
                'aulas',
                'totalEquipos',
                'disponibles',
                'enUso',
                'enMantenimiento',
                'dañados'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los equipos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $aulas = Aula::where('activo', true)->orderBy('nombre')->get();
            
            return view('admin.equipos.create', compact('aulas'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nuevo equipo
     */
    public function store(Request $request)
    {
        try {
            // AGREGAR ESTE LOG TEMPORAL
            \Log::info('Datos recibidos en store:', $request->all());
            
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'tipo' => 'required|in:computadora,proyector,switch,router,impresora,otro',
                'marca' => 'nullable|string|max:100',
                'modelo' => 'nullable|string|max:100',
                'numero_serie' => 'required|string|max:100|unique:equipos,numero_serie',
                'aula_id' => 'nullable|exists:aulas,id',
                'ubicacion_especifica' => 'nullable|string|max:255',
                'estado' => 'required|in:disponible,en_uso,mantenimiento,dañado,dado_de_baja',
                'fecha_adquisicion' => 'nullable|date|before_or_equal:today',
                'observaciones' => 'nullable|string',
                'activo' => 'nullable|boolean',
            ], [
                'nombre.required' => 'El nombre del equipo es obligatorio.',
                'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
                'tipo.required' => 'El tipo de equipo es obligatorio.',
                'tipo.in' => 'El tipo debe ser: computadora, proyector, switch, router, impresora u otro.',
                'marca.max' => 'La marca no puede exceder 100 caracteres.',
                'modelo.max' => 'El modelo no puede exceder 100 caracteres.',
                'numero_serie.required' => 'El número de serie es obligatorio.',
                'numero_serie.max' => 'El número de serie no puede exceder 100 caracteres.',
                'numero_serie.unique' => 'Este número de serie ya está registrado.',
                'aula_id.exists' => 'El aula seleccionada no existe.',
                'ubicacion_especifica.max' => 'La ubicación específica no puede exceder 255 caracteres.',
                'estado.required' => 'El estado del equipo es obligatorio.',
                'estado.in' => 'El estado debe ser: disponible, en_uso, mantenimiento, dañado o dado_de_baja.',
                'fecha_adquisicion.date' => 'La fecha de adquisición debe ser una fecha válida.',
                'fecha_adquisicion.before_or_equal' => 'La fecha de adquisición no puede ser futura.',
            ]);

            // AGREGAR LOG DESPUÉS DE VALIDACIÓN
            \Log::info('Validación pasó OK');

            // Establecer valor por defecto para activo
            $validated['activo'] = $request->has('activo') ? 1 : 0;

            // Generar código de inventario
            $validated['codigo_inventario'] = $this->generarCodigoInventario($validated['tipo']);
            
            // AGREGAR LOG ANTES DE CREAR
            \Log::info('Intentando crear con datos:', $validated);

            $equipo = Equipo::create($validated);
            
            // AGREGAR LOG DESPUÉS DE CREAR
            \Log::info('Equipo creado con ID: ' . $equipo->id);

            return redirect()
                ->route('admin.equipos.index')
                ->with('success', 'Equipo creado exitosamente.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // CAPTURAR ERRORES DE VALIDACIÓN
            \Log::error('Error de validación:', $e->errors());
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Hay errores en el formulario. Por favor revisa los campos marcados.');
                
        } catch (\Exception $e) {
            // CAPTURAR CUALQUIER OTRO ERROR
            \Log::error('Error al crear equipo: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return back()
                ->with('error', 'Error al crear el equipo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $equipo = Equipo::with('aula')->findOrFail($id);
            $aulas = Aula::where('activo', true)->orderBy('nombre')->get();

            // Obtener historial de uso reciente (últimos 30 días)
            $historialUso = HistorialUsoAula::where(function($query) use ($id) {
                $query->whereHas('sesionClase.asistencias', function($q) use ($id) {
                    $q->where('equipo_id', $id);
                });
            })
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->count();

            return view('admin.equipos.edit', compact('equipo', 'aulas', 'historialUso'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar el equipo: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar equipo existente
     */
    public function update(Request $request, $id)
    {
        try {
            $equipo = Equipo::findOrFail($id);

            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'tipo' => 'required|in:computadora,proyector,switch,router,impresora,otro',
                'marca' => 'nullable|string|max:100',
                'modelo' => 'nullable|string|max:100',
                'numero_serie' => 'required|string|max:100|unique:equipos,numero_serie,' . $id,
                'aula_id' => 'nullable|exists:aulas,id',
                'ubicacion_especifica' => 'nullable|string|max:255',
                'estado' => 'required|in:disponible,en_uso,mantenimiento,dañado,dado_de_baja',
                'fecha_adquisicion' => 'nullable|date|before_or_equal:today',
                'observaciones' => 'nullable|string',
                'activo' => 'boolean',
            ], [
                'nombre.required' => 'El nombre del equipo es obligatorio.',
                'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
                'tipo.required' => 'El tipo de equipo es obligatorio.',
                'tipo.in' => 'El tipo debe ser: computadora, proyector, switch, router, impresora u otro.',
                'marca.max' => 'La marca no puede exceder 100 caracteres.',
                'modelo.max' => 'El modelo no puede exceder 100 caracteres.',
                'numero_serie.required' => 'El número de serie es obligatorio.',
                'numero_serie.max' => 'El número de serie no puede exceder 100 caracteres.',
                'numero_serie.unique' => 'Este número de serie ya está registrado en otro equipo.',
                'aula_id.exists' => 'El aula seleccionada no existe.',
                'ubicacion_especifica.max' => 'La ubicación específica no puede exceder 255 caracteres.',
                'estado.required' => 'El estado del equipo es obligatorio.',
                'estado.in' => 'El estado debe ser: disponible, en_uso, mantenimiento, dañado o dado_de_baja.',
                'fecha_adquisicion.date' => 'La fecha de adquisición debe ser una fecha válida.',
                'fecha_adquisicion.before_or_equal' => 'La fecha de adquisición no puede ser futura.',
            ]);

            // Verificar si cambia de aula y está en uso
            if ($request->aula_id != $equipo->aula_id && $equipo->estado === 'en_uso') {
                return back()->withErrors(['aula_id' => 'No se puede cambiar de aula un equipo que está en uso.'])->withInput();
            }

            // Establecer valor de activo
            $validated['activo'] = $request->has('activo') ? true : false;

            $equipo->update($validated);

            return redirect()
                ->route('admin.equipos.index')
                ->with('success', 'Equipo actualizado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el equipo: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Eliminar equipo
     */
    public function destroy($id)
    {
        try {
            $equipo = Equipo::findOrFail($id);

            // Verificar que no esté en uso
            if ($equipo->estado === 'en_uso') {
                return back()->with('error', 'No se puede eliminar un equipo que está en uso actualmente.');
            }

            // Verificar historial de uso reciente (últimos 30 días)
            $usoReciente = DB::table('asistencias')
                ->where('equipo_id', $id)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count();

            if ($usoReciente > 0) {
                return back()->with('error', 'No se puede eliminar un equipo con historial de uso en los últimos 30 días. Considere marcarlo como inactivo.');
            }

            // En lugar de eliminar, marcar como inactivo (soft delete)
            $equipo->update([
                'activo' => false,
                'estado' => 'dado_de_baja',
            ]);

            return redirect()
                ->route('admin.equipos.index')
                ->with('success', 'Equipo dado de baja exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el equipo: ' . $e->getMessage());
        }
    }

    /**
     * Generar código de inventario automático
     */
    private function generarCodigoInventario($tipo)
    {
        $prefijo = match($tipo) {
            'computadora' => 'PC',
            'proyector' => 'PROY',
            'switch' => 'SW',
            'router' => 'RT',
            'impresora' => 'IMP',
            default => 'EQ',
        };

        $ultimoNumero = Equipo::where('codigo_inventario', 'like', "{$prefijo}-%")
            ->count() + 1;

        return $prefijo . '-' . str_pad($ultimoNumero, 4, '0', STR_PAD_LEFT);
    }
}
