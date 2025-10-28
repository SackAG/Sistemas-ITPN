<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialUsoAula extends Model
{
    protected $fillable = [
        'aula_id',
        'tipo_uso',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'usuario_id',
        'grupo_id',
        'sesion_clase_id',
        'uso_libre_id',
        'reservacion_id',
        'descripcion',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i:s',
        'hora_fin' => 'datetime:H:i:s',
    ];

    // Relaciones
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function sesionClase()
    {
        return $this->belongsTo(SesionClase::class);
    }

    public function usoLibre()
    {
        return $this->belongsTo(UsoLibreEquipo::class, 'uso_libre_id');
    }

    public function reservacion()
    {
        return $this->belongsTo(ReservacionEquipo::class, 'reservacion_id');
    }
}
