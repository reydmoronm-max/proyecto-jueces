<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class JornadaAbuelo extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'jornada_abuelos';

    protected $fillable = [
        'nombre_jornada',
        'fecha_programada',
        'estatus',
        'consejo_comunal_id',
        'detalles',
    ];

    protected $casts = [
        'fecha_programada' => 'date',
    ];

    public function consejoComunal()
    {
        return $this->belongsTo(ConsejoComunal::class, 'consejo_comunal_id');
    }
}
