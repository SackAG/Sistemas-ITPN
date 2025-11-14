<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipo;
use App\Models\Aula;
use Carbon\Carbon;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        echo "Iniciando EquipoSeeder...\n";
        
        // Obtener las primeras 3 aulas para asignar equipos
        $aulas = Aula::where('activo', true)->take(3)->get();
        
        if ($aulas->isEmpty()) {
            echo "⚠️ No hay aulas activas. Creando al menos una...\n";
            $aula = Aula::create([
                'nombre' => 'Lab-101',
                'edificio' => 'Edificio A',
                'piso' => 1,
                'capacidad_alumnos' => 30,
                'capacidad_equipos' => 15,
                'tipo' => 'laboratorio',
                'activo' => true,
            ]);
            $aulas = collect([$aula]);
        }
        
        echo "✓ Usando {$aulas->count()} aulas para asignar equipos\n";
        
        $equipos = [
            // Computadoras
            [
                'nombre' => 'Computadora HP ProDesk 01',
                'tipo' => 'computadora',
                'marca' => 'HP',
                'modelo' => 'ProDesk 400 G7',
                'numero_serie' => 'HP-2024-001',
                'estado' => 'disponible',
                'aula_id' => $aulas->first()->id,
                'ubicacion_especifica' => 'Escritorio 1',
                'fecha_adquisicion' => Carbon::now()->subMonths(6),
                'observaciones' => 'Intel Core i5, 8GB RAM, 256GB SSD',
                'activo' => true,
            ],
            [
                'nombre' => 'Computadora Dell OptiPlex 02',
                'tipo' => 'computadora',
                'marca' => 'Dell',
                'modelo' => 'OptiPlex 7080',
                'numero_serie' => 'DELL-2024-002',
                'estado' => 'disponible',
                'aula_id' => $aulas->first()->id,
                'ubicacion_especifica' => 'Escritorio 2',
                'fecha_adquisicion' => Carbon::now()->subMonths(6),
                'observaciones' => 'Intel Core i7, 16GB RAM, 512GB SSD',
                'activo' => true,
            ],
            [
                'nombre' => 'Computadora Lenovo 03',
                'tipo' => 'computadora',
                'marca' => 'Lenovo',
                'modelo' => 'ThinkCentre M720',
                'numero_serie' => 'LEN-2024-003',
                'estado' => 'en_uso',
                'aula_id' => $aulas->first()->id,
                'ubicacion_especifica' => 'Escritorio 3',
                'fecha_adquisicion' => Carbon::now()->subYear(),
                'observaciones' => 'En uso para curso de programación',
                'activo' => true,
            ],
            
            // Proyectores
            [
                'nombre' => 'Proyector Epson PowerLite',
                'tipo' => 'proyector',
                'marca' => 'Epson',
                'modelo' => 'PowerLite 2250U',
                'numero_serie' => 'EPSON-2023-001',
                'estado' => 'disponible',
                'aula_id' => $aulas->count() > 1 ? $aulas->get(1)->id : $aulas->first()->id,
                'ubicacion_especifica' => 'Montado en techo',
                'fecha_adquisicion' => Carbon::now()->subYear(),
                'observaciones' => 'Proyector de 5000 lúmenes, WUXGA',
                'activo' => true,
            ],
            [
                'nombre' => 'Proyector BenQ',
                'tipo' => 'proyector',
                'marca' => 'BenQ',
                'modelo' => 'MH535A',
                'numero_serie' => 'BENQ-2023-002',
                'estado' => 'mantenimiento',
                'aula_id' => $aulas->count() > 2 ? $aulas->get(2)->id : $aulas->first()->id,
                'ubicacion_especifica' => 'Taller de servicio',
                'fecha_adquisicion' => Carbon::now()->subYears(2),
                'observaciones' => 'Requiere cambio de lámpara',
                'activo' => true,
            ],
            
            // Switch de red
            [
                'nombre' => 'Switch Cisco 24 puertos',
                'tipo' => 'switch',
                'marca' => 'Cisco',
                'modelo' => 'Catalyst 2960',
                'numero_serie' => 'CISCO-2022-001',
                'estado' => 'disponible',
                'aula_id' => $aulas->first()->id,
                'ubicacion_especifica' => 'Rack de comunicaciones',
                'fecha_adquisicion' => Carbon::now()->subYears(3),
                'observaciones' => 'Switch administrable de 24 puertos Gigabit',
                'activo' => true,
            ],
            
            // Router
            [
                'nombre' => 'Router TP-Link AC1750',
                'tipo' => 'router',
                'marca' => 'TP-Link',
                'modelo' => 'Archer C7',
                'numero_serie' => 'TPLINK-2023-001',
                'estado' => 'en_uso',
                'aula_id' => $aulas->count() > 1 ? $aulas->get(1)->id : $aulas->first()->id,
                'ubicacion_especifica' => 'Montado en pared',
                'fecha_adquisicion' => Carbon::now()->subMonths(8),
                'observaciones' => 'Router inalámbrico AC1750, Dual Band',
                'activo' => true,
            ],
            
            // Impresoras
            [
                'nombre' => 'Impresora HP LaserJet',
                'tipo' => 'impresora',
                'marca' => 'HP',
                'modelo' => 'LaserJet Pro M404dn',
                'numero_serie' => 'HP-IMP-2024-001',
                'estado' => 'disponible',
                'aula_id' => null,
                'ubicacion_especifica' => 'Oficina administrativa',
                'fecha_adquisicion' => Carbon::now()->subMonths(4),
                'observaciones' => 'Impresora láser monocromática',
                'activo' => true,
            ],
            [
                'nombre' => 'Impresora Canon Multifuncional',
                'tipo' => 'impresora',
                'marca' => 'Canon',
                'modelo' => 'imageRUNNER 2530i',
                'numero_serie' => 'CANON-2023-001',
                'estado' => 'dañado',
                'aula_id' => null,
                'ubicacion_especifica' => 'Centro de copiado',
                'fecha_adquisicion' => Carbon::now()->subYears(2),
                'observaciones' => 'Atasco de papel recurrente, requiere reparación',
                'activo' => false,
            ],
            
            // Equipo de prueba sin aula
            [
                'nombre' => 'Laptop Dell Latitude',
                'tipo' => 'otro',
                'marca' => 'Dell',
                'modelo' => 'Latitude 5420',
                'numero_serie' => 'DELL-LAP-2024-001',
                'estado' => 'disponible',
                'aula_id' => null,
                'ubicacion_especifica' => 'Almacén de equipos',
                'fecha_adquisicion' => Carbon::now()->subMonths(3),
                'observaciones' => 'Laptop para préstamo a profesores',
                'activo' => true,
            ],
        ];
        
        echo "\nInsertando " . count($equipos) . " equipos...\n";
        
        foreach ($equipos as $index => $equipoData) {
            try {
                // Generar código de inventario
                $codigoInventario = $this->generarCodigoInventario($equipoData['tipo'], $index + 1);
                $equipoData['codigo_inventario'] = $codigoInventario;
                
                echo "  → Insertando: {$equipoData['nombre']} (#{$codigoInventario})... ";
                
                $equipo = Equipo::create($equipoData);
                
                echo "✓\n";
            } catch (\Exception $e) {
                echo "✗ ERROR: " . $e->getMessage() . "\n";
                echo "    Datos: " . json_encode($equipoData) . "\n";
            }
        }
        
        $total = Equipo::count();
        echo "\n✓ Seeder completado. Total de equipos en BD: {$total}\n";
    }
    
    /**
     * Generar código de inventario único
     */
    private function generarCodigoInventario($tipo, $numero)
    {
        $prefijos = [
            'computadora' => 'CPU',
            'proyector' => 'PROY',
            'switch' => 'SW',
            'router' => 'RTR',
            'impresora' => 'IMP',
            'otro' => 'EQP',
        ];
        
        $prefijo = $prefijos[$tipo] ?? 'EQP';
        $año = date('Y');
        $codigo = sprintf('%s-%s-%04d', $prefijo, $año, $numero);
        
        // Verificar si existe, si existe agregar sufijo
        $contador = 1;
        $codigoOriginal = $codigo;
        while (Equipo::where('codigo_inventario', $codigo)->exists()) {
            $codigo = $codigoOriginal . '-' . $contador;
            $contador++;
        }
        
        return $codigo;
    }
}
