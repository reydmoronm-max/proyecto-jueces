<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocero extends Model
{
    protected $table = 'voceros';
    protected $fillable = [
        'persona_id',
        'categoria_vocero',
        'fecha_eleccion',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
