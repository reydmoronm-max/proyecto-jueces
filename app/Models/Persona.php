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
        'tipo_enfermedad',
        'pensionado_jubilado',
        'estudia',
        'genero',
        'parentesco'
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

    public function vocerias()
    {
        return $this->hasMany(Vocero::class, 'persona_id');
    }

    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($this->fecha_nacimiento)->age;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function familia()
    {
        return $this->belongsTo(Familia::class, 'familia_id');
    }

    public function consejoComunal()
    {
        return $this->hasOneThrough(
            ConsejoComunal::class,
            Familia::class,
            'id',
            'id',
            'familia_id',
            'consejo_comunal_id'
        );
    }

    public function getConsejoComunalIdAttribute()
    {
        return $this->familia?->consejo_comunal_id;
    }

    public function getViviendaAttribute()
    {
        return $this->familia?->vivienda;
    }

    public function getMisionViviendaAttribute()
    {
        return $this->familia?->mision_vivienda;
    }

    public function getBonoUnicoFamiliarAttribute()
    {
        return $this->familia?->bono_unico_familiar;
    }

    public function getClapAttribute()
    {
        return $this->familia?->clap;
    }
}
