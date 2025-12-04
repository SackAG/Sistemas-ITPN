<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Buscar o crear usuario admin
$user = User::where('email', 'admin@sistema.com')->first();

if ($user) {
    // Actualizar contraseña
    $user->update([
        'password' => Hash::make('admin123'),
        'rol' => 'admin',
    ]);
    echo "\n✅ Contraseña actualizada exitosamente!\n\n";
} else {
    // Crear nuevo usuario
    $user = User::create([
        'name' => 'Administrador Sistema',
        'email' => 'admin@sistema.com',
        'password' => Hash::make('admin123'),
        'rol' => 'admin',
        'email_verified_at' => now(),
    ]);
    echo "\n✅ Usuario administrador creado exitosamente!\n\n";
}

echo "📧 Email: " . $user->email . "\n";
echo "🔑 Contraseña: admin123\n";
echo "👤 Nombre: " . $user->name . "\n\n";
