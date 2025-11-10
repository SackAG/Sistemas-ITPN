<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $fillable = [
        'nombre',
        'clave',
        'carrera_id',
        'semestre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'semestre' => 'integer',
    ];

    // Relaciones
    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function temas()
    {
        return $this->hasMany(Tema::class);
    }
}
