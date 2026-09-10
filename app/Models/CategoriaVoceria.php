<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaVoceria extends Model
{
    protected $table = 'categoria_vocerias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}
