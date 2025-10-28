<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservacionEquipo extends Model
{
    protected $fillable = [
        'alumno_id',
        'equipo_id',
        'aula_id',
        'fecha_reservacion',
        'hora_inicio',
        'hora_fin',
        'motivo',
        'estado',
        'grupo_id',
    ];

    protected $casts = [
        'fecha_reservacion' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    // Relaciones
    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function historial()
    {
        return $this->hasOne(HistorialUsoAula::class, 'reservacion_id');
    }
}
