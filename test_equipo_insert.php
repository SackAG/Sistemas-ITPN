<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Equipo;
use App\Models\Aula;

echo "🧪 Prueba de inserción de equipo...\n\n";

try {
    // Datos de prueba (simulando lo que enviaría el formulario)
    $datos = [
        'nombre' => 'Equipo de Prueba Manual',
        'tipo' => 'computadora',
        'marca' => 'HP',
        'modelo' => 'EliteBook 840',
        'numero_serie' => 'TEST-MANUAL-001',
        'estado' => 'disponible',
        'aula_id' => Aula::first()->id ?? null,
        'ubicacion_especifica' => 'Prueba',
        'fecha_adquisicion' => date('Y-m-d'),
        'observaciones' => 'Equipo de prueba creado manualmente',
        'activo' => true,
        'codigo_inventario' => 'TEST-2025-001',
    ];
    
    echo "📝 Datos a insertar:\n";
    echo json_encode($datos, JSON_PRETTY_PRINT) . "\n\n";
    
    echo "🔄 Creando equipo...\n";
    $equipo = Equipo::create($datos);
    
    echo "✅ ¡Equipo creado exitosamente!\n";
    echo "   ID: {$equipo->id}\n";
    echo "   Nombre: {$equipo->nombre}\n";
    echo "   Código: {$equipo->codigo_inventario}\n";
    echo "   Tipo: {$equipo->tipo}\n";
    echo "   Estado: {$equipo->estado}\n\n";
    
    echo "📊 Total de equipos en BD: " . Equipo::count() . "\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "   Trace:\n" . $e->getTraceAsString() . "\n";
}
