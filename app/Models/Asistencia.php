<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'sesion_clase_id',
        'grupo_id',
        'alumno_id',
        'equipo_id',
        'fecha',
        'estado',
        'hora_entrada',
        'hora_salida',
        'asistio',
        'uso_equipo_personal',
        'observaciones',
        'registrado_por',
    ];

    protected $casts = [
        'asistio' => 'boolean',
        'uso_equipo_personal' => 'boolean',
        'fecha' => 'date',
        'hora_entrada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
    ];

    // Relaciones
    public function sesionClase()
    {
        return $this->belongsTo(SesionClase::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
