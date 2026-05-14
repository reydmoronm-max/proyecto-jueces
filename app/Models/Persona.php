<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Visita;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'cedula',
        'cedula_tipo',
        'nombres',
        'apellidos',
        'telefono',
        'direccion'
    ];

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'persona_id');
    }
}
