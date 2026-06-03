<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citaciones extends Model
{
    protected $table = 'citaciones';

    protected $fillable = [
        'expediente_id',
        'hora_citacion',
        'fecha_citacion',
        'asistio',
        'observaciones',
        'solicita_cambio_id',
        'estatus',
    ];

    protected $casts = [
        'fecha_citacion' => 'date',
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }
}
