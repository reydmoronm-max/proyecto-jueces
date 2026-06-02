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

    public function actas()
    {
        return $this->hasMany(\App\Models\Actas::class, 'expediente_id');
    }

    public function involucrados() {
    return $this->hasMany(Involucrados::class, 'expediente_id');
}
}
