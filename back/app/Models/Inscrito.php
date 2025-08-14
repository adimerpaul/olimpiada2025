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
        // Campos dinámicos para los 10 estudiantes
        'estudiante1', 'ci1', 'tutor1', 'telefono1', 'curso1',
        'estudiante2', 'ci2', 'tutor2', 'telefono2', 'curso2',
        'estudiante3', 'ci3', 'tutor3', 'telefono3', 'curso3',
        'estudiante4', 'ci4', 'tutor4', 'telefono4', 'curso4',
        'estudiante5', 'ci5', 'tutor5', 'telefono5', 'curso5',
        'estudiante6', 'ci6', 'tutor6', 'telefono6', 'curso6',
        'estudiante7', 'ci7', 'tutor7', 'telefono7', 'curso7',
        'estudiante8', 'ci8', 'tutor8', 'telefono8', 'curso8',
        'estudiante9', 'ci9', 'tutor9', 'telefono9', 'curso9',
        'estudiante10', 'ci10', 'tutor10', 'telefono10', 'curso10',
    ];

    /**
     * Relación con el área.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
