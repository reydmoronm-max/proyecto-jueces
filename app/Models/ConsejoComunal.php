<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class ConsejoComunal extends Model implements Auditable
{
    use AuditableTrait;

    protected $table = 'consejos_comunales';
    protected $fillable = [
        'nombre',
        'rif',
        'jefe_comando',
        'direccion',
    ];

    public function jefe()
    {
        return $this->belongsTo(Persona::class, 'jefe_comando');
    }

    public function jefe_comando()
    {
        return $this->jefe();
    }

    public function familias()
    {
        return $this->hasMany(Familia::class, 'consejo_comunal_id');
    }
}
