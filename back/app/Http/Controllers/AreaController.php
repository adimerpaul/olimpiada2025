<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Inscrito;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        return Area::orderBy('area')->get();
    }

    public function show($id)
    {
        return Area::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Normalizar numéricos/booleanos
        [$data['min_integrantes'], $data['max_integrantes']] = $this->normalizeMinMax(
            $data['min_integrantes'] ?? null,
            $data['max_integrantes'] ?? null
        );
        $data['grupo_mismo_curso'] = $this->toBool($data['grupo_mismo_curso'] ?? false);

        // Parsear JSON si vino como string
        $data['cupos_por_grado']   = $this->parseJson($request->input('cupos_por_grado'));
        $data['reglas_especiales'] = $this->parseJson($request->input('reglas_especiales'));

        $area = Area::create($data);

        return response()->json([
            'message' => 'Área creada correctamente.',
            'data'    => $area
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $data = $this->validateData($request);

        // Normalizar numéricos/booleanos
        [$data['min_integrantes'], $data['max_integrantes']] = $this->normalizeMinMax(
            $data['min_integrantes'] ?? $area->min_integrantes,
            $data['max_integrantes'] ?? $area->max_integrantes
        );
        $data['grupo_mismo_curso'] = array_key_exists('grupo_mismo_curso', $data)
            ? $this->toBool($data['grupo_mismo_curso'])
            : $area->grupo_mismo_curso;

        // Parsear JSON si vino como string
        $data['cupos_por_grado']   = $this->parseJson($request->input('cupos_por_grado', $area->cupos_por_grado));
        $data['reglas_especiales'] = $this->parseJson($request->input('reglas_especiales', $area->reglas_especiales));

        $area->update($data);

        return response()->json([
            'message' => 'Área actualizada correctamente.',
            'data'    => $area
        ]);
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);

        $tieneInscritos = Inscrito::where('area_id', $area->id)->exists();
        if ($tieneInscritos) {
            return response()->json([
                'message' => 'No se puede eliminar: existen inscripciones asociadas.'
            ], 422);
        }

        $area->delete();

        return response()->json(['message' => 'Área eliminada correctamente.']);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'area'  => ['required','string','max:150'],

            // cursos visibles en el front (si están null no aparecen)
            'curso1' => ['nullable','string','max:50'],
            'curso2' => ['nullable','string','max:50'],
            'curso3' => ['nullable','string','max:50'],
            'curso4' => ['nullable','string','max:50'],
            'curso5' => ['nullable','string','max:50'],
            'curso6' => ['nullable','string','max:50'],

            'titulo_fecha1' => ['nullable','string','max:100'],
            'fecha1'        => ['nullable','date_format:Y-m-d'],
            'titulo_fecha2' => ['nullable','string','max:100'],
            'fecha2'        => ['nullable','date_format:Y-m-d'],

            'lugar'      => ['nullable','string','max:255'],
            'modalidad'  => ['nullable','string','max:100'],
            'inscripcion'=> ['nullable','string'],

            // Aceptamos objeto o string JSON
            'cupos_por_grado'   => ['nullable'],
            'reglas_especiales' => ['nullable'],

            // >>> NUEVO: límites y regla de mismo curso
            'min_integrantes'    => ['nullable','integer','min:1'],
            'max_integrantes'    => ['nullable','integer','min:1'],
            'grupo_mismo_curso'  => ['nullable'], // lo normalizamos a bool aparte
        ], [
            'area.required'      => 'El nombre del área es obligatorio.',
            'fecha1.date_format' => 'La Fecha 1 debe tener el formato YYYY-MM-DD.',
            'fecha2.date_format' => 'La Fecha 2 debe tener el formato YYYY-MM-DD.',
            'min_integrantes.min'=> 'El mínimo de integrantes debe ser al menos 1.',
            'max_integrantes.min'=> 'El máximo de integrantes debe ser al menos 1.',
        ]);
    }

    private function parseJson($value)
    {
        if (is_array($value) || is_null($value)) return $value;
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }
        return null;
    }

    private function toBool($v): bool
    {
        // Acepta true/false, 1/0, "1"/"0", "true"/"false", "on"/"off"
        if (is_bool($v)) return $v;
        if (is_numeric($v)) return (int)$v === 1;
        $s = strtolower((string)$v);
        return in_array($s, ['1','true','on','yes','si','sí'], true);
    }

    /**
     * Normaliza y asegura: min >= 1 y max >= min. Si vienen null, usa defaults (1 y 10).
     */
    private function normalizeMinMax($min, $max): array
    {
        $min = (int)($min ?? 1);
        $max = (int)($max ?? 10);
        if ($min < 1) $min = 1;
        if ($max < $min) $max = $min;
        return [$min, $max];
    }
}
