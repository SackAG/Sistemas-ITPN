<?php

namespace App\Observers;

use App\Models\SesionClase;
use App\Models\HistorialUsoAula;

class SesionClaseObserver
{
    /**
     * Handle the SesionClase "created" event.
     */
    public function created(SesionClase $sesionClase): void
    {
        // Obtener el aula y materia desde la asignación
        $asignacion = $sesionClase->asignacionAula;
        $materia = $asignacion->grupo->materia;
        
        // Crear automáticamente registro en historial_uso_aulas
        HistorialUsoAula::create([
            'aula_id' => $asignacion->aula_id,
            'tipo_uso' => 'clase',
            'fecha' => $sesionClase->fecha_sesion,
            'hora_inicio' => $sesionClase->hora_inicio_real,
            'hora_fin' => $sesionClase->hora_fin_real,
            'usuario_id' => $sesionClase->profesor_id,
            'grupo_id' => $asignacion->grupo_id,
            'sesion_clase_id' => $sesionClase->id,
            'descripcion' => "Clase de {$materia->nombre} - {$sesionClase->tipo_actividad}",
        ]);
    }

    /**
     * Handle the SesionClase "updated" event.
     */
    public function updated(SesionClase $sesionClase): void
    {
        // Actualizar el historial correspondiente si existe
        $historial = HistorialUsoAula::where('sesion_clase_id', $sesionClase->id)->first();
        
        if ($historial) {
            $historial->update([
                'hora_inicio' => $sesionClase->hora_inicio_real,
                'hora_fin' => $sesionClase->hora_fin_real,
                'fecha' => $sesionClase->fecha_sesion,
            ]);
        }
    }

    /**
     * Handle the SesionClase "deleted" event.
     */
    public function deleted(SesionClase $sesionClase): void
    {
        //
    }

    /**
     * Handle the SesionClase "restored" event.
     */
    public function restored(SesionClase $sesionClase): void
    {
        //
    }

    /**
     * Handle the SesionClase "force deleted" event.
     */
    public function forceDeleted(SesionClase $sesionClase): void
    {
        //
    }
}
