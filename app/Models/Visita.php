<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;

class Visita extends Model
{
    use HasFactory;

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
