<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Proyecto extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

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
