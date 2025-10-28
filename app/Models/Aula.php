<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $fillable = [
        'nombre',
        'edificio',
        'capacidad_alumnos',
        'capacidad_equipos',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'capacidad_alumnos' => 'integer',
        'capacidad_equipos' => 'integer',
    ];

    // Relaciones
    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionAula::class);
    }

    public function usosLibres()
    {
        return $this->hasMany(UsoLibreEquipo::class);
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
