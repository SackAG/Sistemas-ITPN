<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    protected $fillable = [
        'materia_id',
        'numero_tema',
        'nombre',
        'descripcion',
    ];

    protected $casts = [
        'numero_tema' => 'integer',
    ];

    // Relaciones
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function sesiones()
    {
        return $this->hasMany(SesionClase::class);
    }
}
