<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsoLibreEquipo extends Model
{
    protected $fillable = [
        'alumno_id',
        'equipo_id',
        'aula_id',
        'fecha_uso',
        'hora_inicio',
        'hora_fin',
        'motivo',
        'descripcion',
        'autorizado_por',
    ];

    protected $casts = [
        'fecha_uso' => 'date',
        'hora_inicio' => 'datetime:H:i:s',
        'hora_fin' => 'datetime:H:i:s',
    ];

    // Relaciones
    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function autorizadoPor()
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    public function historial()
    {
        return $this->hasOne(HistorialUsoAula::class, 'uso_libre_id');
    }
}
