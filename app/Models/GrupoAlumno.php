<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoAlumno extends Model
{
    protected $table = 'grupo_alumno';

    protected $fillable = [
        'grupo_id',
        'alumno_id',
        'fecha_inscripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inscripcion' => 'date',
    ];

    // Relaciones
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}
