<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionAula extends Model
{
    protected $table = 'asignaciones_aula';

    protected $fillable = [
        'aula_id',
        'grupo_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'fecha_inicio_vigencia',
        'fecha_fin_vigencia',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'fecha_inicio_vigencia' => 'date',
        'fecha_fin_vigencia' => 'date',
    ];

    // Relaciones
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function sesiones()
    {
        return $this->hasMany(SesionClase::class);
    }
}
