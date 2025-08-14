<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model{
    use SoftDeletes;
//$table->id();
//$table->string('area')->nullable();
//$table->string('curso1')->nullable();
//$table->string('curso2')->nullable();
//$table->string('curso3')->nullable();
//$table->string('curso4')->nullable();
//$table->string('curso5')->nullable();
//$table->string('curso6')->nullable();
//$table->string('titulo_fecha1')->nullable();
//$table->date('fecha1')->nullable();
//$table->string('titulo_fecha2')->nullable();
//$table->date('fecha2')->nullable();
//$table->text('lugar')->nullable();
//$table->string('modalidad')->nullable();
//$table->text('inscripcion')->nullable();
//$table->softDeletes();
//$table->timestamps();
    protected $fillable = [
        'area',
        'curso1',
        'curso2',
        'curso3',
        'curso4',
        'curso5',
        'curso6',
        'titulo_fecha1',
        'fecha1',
        'titulo_fecha2',
        'fecha2',
        'lugar',
        'modalidad',
        'inscripcion',
    ];
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
