<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'sector_productivo',
        'presupuesto',
        'responsable',
        'fecha_inicio',
        'estatus',
        'descripcion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'presupuesto' => 'decimal:2',
    ];
}
