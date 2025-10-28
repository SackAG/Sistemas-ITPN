<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'sesion_clase_id',
        'alumno_id',
        'equipo_id',
        'hora_entrada',
        'hora_salida',
        'asistio',
        'uso_equipo_personal',
        'observaciones',
    ];

    protected $casts = [
        'asistio' => 'boolean',
        'uso_equipo_personal' => 'boolean',
        'hora_entrada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
    ];

    // Relaciones
    public function sesionClase()
    {
        return $this->belongsTo(SesionClase::class);
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
