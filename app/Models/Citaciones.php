<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Citaciones extends Model implements Auditable
{
    use AuditableTrait;

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

    public function solicitaCambio()
    {
        return $this->belongsTo(Persona::class, 'solicita_cambio_id');
    }
}
