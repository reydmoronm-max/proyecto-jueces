<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Visita extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'persona_id',
        'proposito',
        'de_parte'
    ];

        public function persona()
        {
            return $this->belongsTo(Persona::class, 'persona_id');
        }
}
