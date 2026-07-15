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
        'lo_atiende_juez_id'
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }

    public function juez()
    {
        return $this->belongsTo(User::class, 'lo_atiende_juez_id');
    }
}
