<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Involucrados extends Model implements Auditable
{
    use AuditableTrait;

    protected $table = 'involucrados';

    protected $fillable = [
        'persona_id',
        'expediente_id',
        'rol',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expediente_id');
    }
}
