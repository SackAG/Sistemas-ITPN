<?php

namespace App\Observers;

use App\Models\ReservacionEquipo;
use App\Models\HistorialUsoAula;

class ReservacionEquipoObserver
{
    /**
     * Handle the ReservacionEquipo "created" event.
     */
    public function created(ReservacionEquipo $reservacionEquipo): void
    {
        //
    }

    /**
     * Handle the ReservacionEquipo "updated" event.
     */
    public function updated(ReservacionEquipo $reservacionEquipo): void
    {
        // Solo crear historial cuando el estado cambia de 'pendiente' a 'en_uso'
        if ($reservacionEquipo->isDirty('estado') && 
            $reservacionEquipo->estado === 'en_uso' && 
            $reservacionEquipo->getOriginal('estado') === 'pendiente') {
            
            HistorialUsoAula::create([
                'aula_id' => $reservacionEquipo->aula_id,
                'tipo_uso' => 'reservado',
                'fecha' => $reservacionEquipo->fecha_reservacion,
                'hora_inicio' => $reservacionEquipo->hora_inicio,
                'hora_fin' => $reservacionEquipo->hora_fin,
                'usuario_id' => $reservacionEquipo->alumno_id,
                'grupo_id' => $reservacionEquipo->grupo_id,
                'reservacion_id' => $reservacionEquipo->id,
                'descripcion' => "Reservación de equipo activada",
                'observaciones' => $reservacionEquipo->motivo,
            ]);
        }
    }

    /**
     * Handle the ReservacionEquipo "deleted" event.
     */
    public function deleted(ReservacionEquipo $reservacionEquipo): void
    {
        //
    }

    /**
     * Handle the ReservacionEquipo "restored" event.
     */
    public function restored(ReservacionEquipo $reservacionEquipo): void
    {
        //
    }

    /**
     * Handle the ReservacionEquipo "force deleted" event.
     */
    public function forceDeleted(ReservacionEquipo $reservacionEquipo): void
    {
        //
    }
}
