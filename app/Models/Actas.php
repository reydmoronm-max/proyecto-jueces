<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actas extends Model
{
    protected $table = 'actas';

    protected $fillable = [
        'expediente_id',
        'tipo_acta',
        'contenido',
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }
}
