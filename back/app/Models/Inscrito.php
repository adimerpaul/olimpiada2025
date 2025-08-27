<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inscrito extends Model{
    use HasFactory, SoftDeletes;

    protected $table = 'inscritos';

    protected $fillable = [
        'area_id',
        'grupo_nombre',
        'max_integrantes',
        'pago1',

        // Datos del tutor/profesor(a)
        'tutor_paterno',
        'tutor_materno',
        'tutor_nombre',
        'tutor_celular',
        'tutor_correo',

        // Unidad educativa
        'colegio',
        'ciudad',

        // ---- Estudiantes (1..10) ----
        'estudiante_nombre1','estudiante_paterno1','ci1','tutor1','telefono1','curso1',
        'estudiante_nombre2','estudiante_paterno2','ci2','tutor2','telefono2','curso2',
        'estudiante_nombre3','estudiante_paterno3','ci3','tutor3','telefono3','curso3',
        'estudiante_nombre4','estudiante_paterno4','ci4','tutor4','telefono4','curso4',
        'estudiante_nombre5','estudiante_paterno5','ci5','tutor5','telefono5','curso5',
        'estudiante_nombre6','estudiante_paterno6','ci6','tutor6','telefono6','curso6',
        'estudiante_nombre7','estudiante_paterno7','ci7','tutor7','telefono7','curso7',
        'estudiante_nombre8','estudiante_paterno8','ci8','tutor8','telefono8','curso8',
        'estudiante_nombre9','estudiante_paterno9','ci9','tutor9','telefono9','curso9',
        'estudiante_nombre10','estudiante_paterno10','ci10','tutor10','telefono10','curso10',
    ];

    /**
     * Relación con el área.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
