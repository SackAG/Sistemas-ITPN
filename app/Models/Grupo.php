<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'materia_id',
        'profesor_id',
        'clave_grupo',
        'periodo',
        'año',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'año' => 'integer',
    ];

    // Relaciones
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function alumnos()
    {
        return $this->belongsToMany(User::class, 'grupo_alumno', 'grupo_id', 'alumno_id')
                    ->withPivot('fecha_inscripcion', 'activo')
                    ->withTimestamps();
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionAula::class);
    }

    public function reservaciones()
    {
        return $this->hasMany(ReservacionEquipo::class);
    }

    public function historialUsos()
    {
        return $this->hasMany(HistorialUsoAula::class);
    }
}
