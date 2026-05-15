<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citaciones extends Model
{
    protected $table = 'citaciones';

    protected $fillable = [
        'expediente_id',
        'numero_citacion',
        'fecha_citacion',
        'asistio',
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }
}
