<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $fillable = [
        'nombre',
        'clave',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Alias para usuarios (mismo que users)
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function materias()
    {
        return $this->hasMany(Materia::class);
    }
}
