<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de configuraciones del sistema.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'theme', 'value' => 'light'],
            ['key' => 'app_name', 'value' => 'Sistema de Control de Asistencia e Inventario'],
            ['key' => 'timezone', 'value' => 'America/Mexico_City'],
            ['key' => 'items_per_page', 'value' => '10'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        $this->command->info('✅ Configuraciones del sistema creadas exitosamente.');
    }
}
