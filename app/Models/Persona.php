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
        'direccion',
        'familia_id',
        'fecha_nacimiento',
        'centro_votacion',
        'carnet_patria',
        'nivel_academico',
        'profesion',
        'situacion_laboral',
        'vivienda',
        'tipo_enfermedad',
        'bono_unico_familiar',
        'pensionado_jubilado',
        'ayuda_tecnica',
        'mision_vivienda',
        'clap',
        'casa_alimentacion',
        'estudia',
        'genero',
        'parentesco',
        'consejo_comunal_id'
    ];

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'persona_id');
    }

    public function expedientes()
    {
        return $this->belongsToMany(Expediente::class, 'involucrados');
    }

    public function consejosComunales()
    {
        return $this->hasMany(ConsejoComunal::class, 'jefe_comando');
    }

    public function familia()
    {
        return $this->belongsTo(Familia::class, 'familia_id');
    }

    public function consejoComunal()
    {
        return $this->belongsTo(ConsejoComunal::class, 'consejo_comunal_id');
    }
}
