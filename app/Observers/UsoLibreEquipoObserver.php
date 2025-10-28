<?php

namespace App\Observers;

use App\Models\UsoLibreEquipo;
use App\Models\HistorialUsoAula;

class UsoLibreEquipoObserver
{
    /**
     * Handle the UsoLibreEquipo "created" event.
     */
    public function created(UsoLibreEquipo $usoLibreEquipo): void
    {
        // Crear automáticamente registro en historial_uso_aulas
        HistorialUsoAula::create([
            'aula_id' => $usoLibreEquipo->aula_id,
            'tipo_uso' => 'uso_libre',
            'fecha' => $usoLibreEquipo->fecha_uso,
            'hora_inicio' => $usoLibreEquipo->hora_inicio,
            'hora_fin' => $usoLibreEquipo->hora_fin, // Puede ser NULL si aún no termina
            'usuario_id' => $usoLibreEquipo->alumno_id,
            'uso_libre_id' => $usoLibreEquipo->id,
            'descripcion' => "Uso libre de equipo - Motivo: {$usoLibreEquipo->motivo}",
        ]);
    }

    /**
     * Handle the UsoLibreEquipo "updated" event.
     */
    public function updated(UsoLibreEquipo $usoLibreEquipo): void
    {
        // Actualizar hora_fin en el historial cuando se registra
        $historial = HistorialUsoAula::where('uso_libre_id', $usoLibreEquipo->id)->first();
        
        if ($historial) {
            $historial->update([
                'hora_fin' => $usoLibreEquipo->hora_fin,
            ]);
        }
    }

    /**
     * Handle the UsoLibreEquipo "deleted" event.
     */
    public function deleted(UsoLibreEquipo $usoLibreEquipo): void
    {
        //
    }

    /**
     * Handle the UsoLibreEquipo "restored" event.
     */
    public function restored(UsoLibreEquipo $usoLibreEquipo): void
    {
        //
    }

    /**
     * Handle the UsoLibreEquipo "force deleted" event.
     */
    public function forceDeleted(UsoLibreEquipo $usoLibreEquipo): void
    {
        //
    }
}
