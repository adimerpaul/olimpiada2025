<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Inscrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InscritoController extends Controller
{
    // Máximo de áreas distintas permitidas por estudiante (por CI)
    private int $MAX_AREAS = 3;

    public function areasPorCi(string $ci)
    {
        // Busca el CI en cualquiera de las columnas ci1..ci10 y devuelve cuántas áreas distintas tiene
        $areaCount = Inscrito::query()
            ->where(function ($q) use ($ci) {
                for ($i = 1; $i <= 10; $i++) {
                    $q->orWhere("ci{$i}", $ci);
                }
            })
            ->distinct('area_id')
            ->count('area_id');

        return response()->json(['ci' => $ci, 'areas' => $areaCount]);
    }

    public function store(Request $request)
    {
        $area = Area::findOrFail($request->area_id);

        // Cursos válidos para el área (solo los que no son null)
        $validCursos = [];
        for ($i = 1; $i <= 6; $i++) {
            if (!empty($area->{"curso{$i}"})) {
                $validCursos[] = $area->{"curso{$i}"};
            }
        }

        // Validación
        $validated = $request->validate([
            'area_id'        => ['required', 'exists:areas,id'],
            'grupo_nombre'   => ['nullable', 'string', 'max:255'],
            // integrantes: 1..10 (tu tabla tiene 10 slots). Si quieres depender del área, cambia 10 por ($area->max_integrantes ?? 10)
            'integrantes'    => ['required', 'array', 'min:1', 'max:10'],
            'integrantes.*.nombre'   => ['required', 'string', 'max:255'],
            'integrantes.*.ci'       => ['required', 'string', 'max:20'],
            'integrantes.*.tutor'    => ['nullable', 'string', 'max:255'],
            'integrantes.*.telefono' => ['nullable', 'string', 'max:20'],
            'integrantes.*.curso'    => ['required', 'string', Rule::in($validCursos)],

            // pago1: archivo opcional (comprobante). Acepta imágenes o PDF.
            'pago1'          => ['nullable', 'file', 'max:5120', 'mimes:png,jpg,jpeg,pdf'],
        ], [
            'integrantes.*.curso.in' => 'El curso seleccionado no es válido para esta área.'
        ]);

        // Reglas: cada CI no puede superar 3 áreas distintas
        // (si ya está en >=3 áreas y esta nueva área es una cuarta, rechazamos)
        $ciList = collect($validated['integrantes'])->pluck('ci')->unique()->values();

        foreach ($ciList as $ci) {
            $areasActuales = Inscrito::query()
                ->where(function ($q) use ($ci) {
                    for ($i = 1; $i <= 10; $i++) {
                        $q->orWhere("ci{$i}", $ci);
                    }
                })
                ->distinct('area_id')
                ->pluck('area_id')
                ->toArray();

            // Si YA está en esta misma área, evitamos duplicados del mismo CI en la misma área
            if (in_array($validated['area_id'], $areasActuales, true)) {
                return response()->json([
                    'message' => "El CI {$ci} ya está inscrito en esta área."
                ], 422);
            }

            // Si ya tiene 3 áreas diferentes, bloquear
            if (count($areasActuales) >= $this->MAX_AREAS) {
                return response()->json([
                    'message' => "El CI {$ci} ya alcanzó el máximo de {$this->MAX_AREAS} áreas permitidas."
                ], 422);
            }
        }

        // Crear registro
        $inscrito = new Inscrito();
        $inscrito->area_id = $validated['area_id'];
        $inscrito->grupo_nombre = $validated['grupo_nombre'] ?? null;
        $inscrito->max_integrantes = 10; // o $area->max_integrantes si lo agregas a areas

        // Mapear integrantes a columnas estudiante*, ci*, tutor*, telefono*, curso*
        foreach ($validated['integrantes'] as $idx => $data) {
            $pos = $idx + 1;
            $inscrito->{"estudiante{$pos}"} = $data['nombre'];
            $inscrito->{"ci{$pos}"}         = $data['ci'];
            $inscrito->{"tutor{$pos}"}      = $data['tutor'] ?? null;
            $inscrito->{"telefono{$pos}"}   = $data['telefono'] ?? null;
            $inscrito->{"curso{$pos}"}      = $data['curso'];
        }

        // Comprobante (pago1) opcional
        if ($request->hasFile('pago1')) {
            $path = $request->file('pago1')->store('comprobantes', 'public'); // storage/app/public/comprobantes
            $inscrito->pago1 = $path; // en tu migración pago1 es string -> guarda la ruta
        }

        $inscrito->save();

        return response()->json([
            'message' => 'Inscripción registrada correctamente',
            'data' => $inscrito
        ], 201);
    }
}
