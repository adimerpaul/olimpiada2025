<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model{
    use SoftDeletes;
    protected $fillable = [
        'area',
        'curso',
        'fecha',
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
