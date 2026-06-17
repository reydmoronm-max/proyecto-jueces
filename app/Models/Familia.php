<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;

class Familia extends Model
{
    use HasFactory;

    protected $table = 'familias';

    protected $fillable = [
        'numero_familia'
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class, 'familia_id');
    }
}
