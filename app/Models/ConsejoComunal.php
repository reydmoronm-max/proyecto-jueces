<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsejoComunal extends Model
{
    protected $table = 'consejos_comunales';
    protected $fillable = [
        'nombre',
        'rif',
        'jefe_comando',
        'direccion',
    ];

    public function jefe_comando()
    {
        return $this->belongsTo(Persona::class, 'jefe_comando');
    }
}
