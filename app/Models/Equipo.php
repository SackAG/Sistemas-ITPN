<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'nombre',
        'codigo_inventario',
        'tipo',
        'marca',
        'modelo',
        'numero_serie',
        'codigo_inventario',
        'estado',
        'aula_id',
        'ubicacion_en_aula',
        'ubicacion_especifica',
        'propiedad',
        'propietario_id',
        'fecha_adquisicion',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function propietario()
    {
        return $this->belongsTo(User::class, 'propietario_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function usosLibres()
    {
        return $this->hasMany(UsoLibreEquipo::class);
    }

    public function reservaciones()
    {
        return $this->hasMany(ReservacionEquipo::class);
    }
}
