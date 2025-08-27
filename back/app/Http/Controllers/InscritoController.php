<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Inscrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InscritoController extends Controller
{
    private int $MAX_AREAS = 3; // por CI

    public function publicShow($id)
    {
        $inscrito = Inscrito::with('area')->findOrFail($id);
        return response()->json($inscrito);
    }

    public function count()
    {
        return response()->json(['count' => Inscrito::count()]);
    }

    public function index()
    {
        return response()->json(Inscrito::with('area')->get());
    }

    public function show($id)
    {
        return response()->json(Inscrito::with('area')->findOrFail($id));
    }

    public function areasPorCi(string $ci)
    {
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

        // Cursos permitidos
        $validCursos = [];
        for ($i = 1; $i <= 6; $i++) {
            if (!empty($area->{"curso{$i}"})) $validCursos[] = $area->{"curso{$i}"};
        }

        $min = max(1, (int)($area->min_integrantes ?? 1));
        $max = max($min, (int)($area->max_integrantes ?? 10));

        $validated = $request->validate([
            'area_id'      => ['required', 'exists:areas,id'],
            'grupo_nombre' => ['nullable', 'string', 'max:255'],

            // Tutor / UE
            'tutor_paterno' => ['nullable', 'string', 'max:255'],
            'tutor_materno' => ['nullable', 'string', 'max:255'],
            'tutor_nombre'  => ['nullable', 'string', 'max:255'],
            'tutor_celular' => ['nullable', 'string', 'max:30'],
            'tutor_correo'  => ['nullable', 'email', 'max:255'],
            'colegio'       => ['nullable', 'string', 'max:255'],
            'ciudad'        => ['nullable', 'string', 'max:255'],

            // Integrantes
            'integrantes'            => ['required', 'array', "min:{$min}", "max:{$max}"],
            'integrantes.*.apellidos'=> ['required', 'string', 'max:255'],
            'integrantes.*.nombres'  => ['required', 'string', 'max:255'],
            'integrantes.*.ci'       => ['required', 'string', 'max:20'],
            'integrantes.*.telefono' => ['nullable', 'string', 'max:20'],
            'integrantes.*.curso'    => ['required', 'string', Rule::in($validCursos)],

            // Comprobante
            'pago1' => ['nullable', 'file', 'max:5120', 'mimes:png,jpg,jpeg,pdf'],
        ], [
            'integrantes.min' => "Debes registrar al menos {$min} integrante(s) para esta área.",
            'integrantes.max' => "Máximo {$max} integrante(s) para esta área.",
        ]);

        // Regla 3 áreas por CI
        $ciList = collect($validated['integrantes'])->pluck('ci')->unique()->values();
        foreach ($ciList as $ci) {
            $areasActuales = Inscrito::query()
                ->where(function ($q) use ($ci) {
                    for ($i = 1; $i <= 10; $i++) $q->orWhere("ci{$i}", $ci);
                })
                ->distinct('area_id')
                ->pluck('area_id')
                ->toArray();

            if (in_array($validated['area_id'], $areasActuales, true)) {
                return response()->json(['message' => "El CI {$ci} ya está inscrito en esta área."], 422);
            }
            if (count($areasActuales) >= $this->MAX_AREAS) {
                return response()->json(['message' => "El CI {$ci} ya alcanzó el máximo de {$this->MAX_AREAS} áreas permitidas."], 422);
            }
        }

        // Crear
        $inscrito = new Inscrito();
        $inscrito->area_id       = $validated['area_id'];
        $inscrito->grupo_nombre  = $validated['grupo_nombre'] ?? null;
        $inscrito->max_integrantes = 10;

        // Tutor y UE
        $inscrito->tutor_paterno = $request->input('tutor_paterno');
        $inscrito->tutor_materno = $request->input('tutor_materno');
        $inscrito->tutor_nombre  = $request->input('tutor_nombre');
        $inscrito->tutor_celular = $request->input('tutor_celular');
        $inscrito->tutor_correo  = $request->input('tutor_correo');
        $inscrito->colegio       = $request->input('colegio');
        $inscrito->ciudad        = $request->input('ciudad');

        // Mapear integrantes
        foreach ($validated['integrantes'] as $idx => $data) {
            $pos = $idx + 1;
            $inscrito->{"estudiante_paterno{$pos}"} = $data['apellidos'] ?? null; // APELLIDOS (campo de migración: *_paterno)
            $inscrito->{"estudiante_nombre{$pos}"}  = $data['nombres']   ?? null;
            $inscrito->{"ci{$pos}"}                 = $data['ci']        ?? null;
            $inscrito->{"telefono{$pos}"}           = $data['telefono']  ?? null;
            $inscrito->{"curso{$pos}"}              = $data['curso']     ?? null;
            // si quieres guardar tutor por estudiante: $inscrito->{"tutor{$pos}"} = $data['tutor'] ?? null;
        }

        if ($request->hasFile('pago1')) {
            $inscrito->pago1 = $request->file('pago1')->store('comprobantes', 'public');
        }

        $inscrito->save();

        return response()->json(['message' => 'Inscripción registrada correctamente', 'inscrito' => $inscrito], 201);
    }

    public function update(Request $request, $id)
    {
        $inscrito = Inscrito::findOrFail($id);

        $areaId = $request->input('area_id', $inscrito->area_id);
        $area   = Area::findOrFail($areaId);

        // Cursos permitidos
        $validCursos = [];
        for ($i = 1; $i <= 6; $i++) {
            if (!empty($area->{"curso{$i}"})) $validCursos[] = $area->{"curso{$i}"};
        }

        $min = max(1, (int)($area->min_integrantes ?? 1));
        $max = max($min, (int)($area->max_integrantes ?? 10));

        $validated = $request->validate([
            'area_id'      => ['required', 'exists:areas,id'],
            'grupo_nombre' => ['nullable', 'string', 'max:255'],

            'tutor_paterno' => ['nullable', 'string', 'max:255'],
            'tutor_materno' => ['nullable', 'string', 'max:255'],
            'tutor_nombre'  => ['nullable', 'string', 'max:255'],
            'tutor_celular' => ['nullable', 'string', 'max:30'],
            'tutor_correo'  => ['nullable', 'email', 'max:255'],
            'colegio'       => ['nullable', 'string', 'max:255'],
            'ciudad'        => ['nullable', 'string', 'max:255'],

            'integrantes'            => ['required', 'array', "min:{$min}", "max:{$max}"],
            'integrantes.*.apellidos'=> ['required', 'string', 'max:255'],
            'integrantes.*.nombres'  => ['required', 'string', 'max:255'],
            'integrantes.*.ci'       => ['required', 'string', 'max:20'],
            'integrantes.*.telefono' => ['nullable', 'string', 'max:20'],
            'integrantes.*.curso'    => ['required', 'string', Rule::in($validCursos)],

            'pago1' => ['nullable', 'file', 'max:5120', 'mimes:png,jpg,jpeg,pdf'],
        ], [
            'integrantes.min' => "Debes registrar al menos {$min} integrante(s) para esta área.",
            'integrantes.max' => "Máximo {$max} integrante(s) para esta área.",
        ]);

        // Validación 3 áreas por CI (excluyendo este registro)
        $ciList = collect($validated['integrantes'])->pluck('ci')->unique()->values();
        foreach ($ciList as $ci) {
            $areasActuales = Inscrito::query()
                ->where('id', '<>', $inscrito->id)
                ->where(function ($q) use ($ci) {
                    for ($i = 1; $i <= 10; $i++) $q->orWhere("ci{$i}", $ci);
                })
                ->distinct('area_id')
                ->pluck('area_id')
                ->toArray();

            if (!in_array((int)$areaId, $areasActuales, true) && count($areasActuales) >= $this->MAX_AREAS) {
                return response()->json(['message' => "El CI {$ci} ya alcanzó el máximo de {$this->MAX_AREAS} áreas permitidas."], 422);
            }
        }

        // Base
        $inscrito->area_id        = $areaId;
        $inscrito->grupo_nombre   = $validated['grupo_nombre'] ?? null;

        // Tutor/UE
        $inscrito->tutor_paterno = $request->input('tutor_paterno');
        $inscrito->tutor_materno = $request->input('tutor_materno');
        $inscrito->tutor_nombre  = $request->input('tutor_nombre');
        $inscrito->tutor_celular = $request->input('tutor_celular');
        $inscrito->tutor_correo  = $request->input('tutor_correo');
        $inscrito->colegio       = $request->input('colegio');
        $inscrito->ciudad        = $request->input('ciudad');

        // Limpiar slots
        for ($i = 1; $i <= 10; $i++) {
            $inscrito->{"estudiante_nombre{$i}"}  = null;
            $inscrito->{"estudiante_paterno{$i}"} = null;
            $inscrito->{"ci{$i}"} = null;
            $inscrito->{"tutor{$i}"} = null;
            $inscrito->{"telefono{$i}"} = null;
            $inscrito->{"curso{$i}"} = null;
        }

        // Re-mapear
        foreach ($validated['integrantes'] as $idx => $data) {
            $pos = $idx + 1;
            $inscrito->{"estudiante_paterno{$pos}"} = $data['apellidos'] ?? null;
            $inscrito->{"estudiante_nombre{$pos}"}  = $data['nombres']   ?? null;
            $inscrito->{"ci{$pos}"}                 = $data['ci']        ?? null;
            $inscrito->{"telefono{$pos}"}           = $data['telefono']  ?? null;
            $inscrito->{"curso{$pos}"}              = $data['curso']     ?? null;
        }

        if ($request->hasFile('pago1')) {
            if ($inscrito->pago1) Storage::disk('public')->delete($inscrito->pago1);
            $inscrito->pago1 = $request->file('pago1')->store('comprobantes', 'public');
        }

        $inscrito->save();

        return response()->json([
            'message' => 'Inscripción actualizada correctamente',
            'data' => $inscrito->load('area')
        ]);
    }

    public function destroy($id)
    {
        $inscrito = Inscrito::findOrFail($id);
        $inscrito->delete();
        return response()->json(['message' => 'Inscrito eliminado correctamente']);
    }
}
