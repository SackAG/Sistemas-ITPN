<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionClase extends Model
{
    protected $table = 'sesiones_clase';

    protected $fillable = [
        'asignacion_aula_id',
        'tema_id',
        'fecha_sesion',
        'hora_inicio_real',
        'hora_fin_real',
        'tipo_actividad',
        'descripcion',
        'profesor_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_sesion' => 'date',
        'hora_inicio_real' => 'datetime:H:i',
        'hora_fin_real' => 'datetime:H:i',
    ];

    // Relaciones
    public function asignacionAula()
    {
        return $this->belongsTo(AsignacionAula::class, 'asignacion_aula_id');
    }

    public function tema()
    {
        return $this->belongsTo(Tema::class);
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function historial()
    {
        return $this->hasOne(HistorialUsoAula::class, 'sesion_clase_id');
    }
}
