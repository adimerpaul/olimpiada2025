<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $table = 'areas';

    protected $fillable = [
        'area',
        'curso1','curso2','curso3','curso4','curso5','curso6',
        'titulo_fecha1','fecha1',
        'titulo_fecha2','fecha2',
        'lugar','modalidad','inscripcion',
        'cupos_por_grado',       // JSON
        'reglas_especiales',     // JSON
    ];

    protected $casts = [
        'fecha1' => 'date:Y-m-d',
        'fecha2' => 'date:Y-m-d',
        'cupos_por_grado'   => 'array',
        'reglas_especiales' => 'array',
    ];

    protected $hidden = [
        'deleted_at',
        // Si prefieres ver created_at/updated_at, comenta estas dos
        'created_at',
        'updated_at',
    ];
}
