<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $fillable = [
        'motivo_denuncia',
        'estatus',
    ];

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'involucrados');
    }
}
