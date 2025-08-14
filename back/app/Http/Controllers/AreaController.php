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

        $data['cupos_por_grado']   = $this->parseJson($request->input('cupos_por_grado'));
        $data['reglas_especiales'] = $this->parseJson($request->input('reglas_especiales'));

        $area->update($data);

        return response()->json([
            'message' => 'Área actualizada correctamente.',
            'data'    => $area
        ]);
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);

        // (Opcional) bloquear si hay inscritos usando esta área
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
        ], [
            'area.required'      => 'El nombre del área es obligatorio.',
            'fecha1.date_format' => 'La Fecha 1 debe tener el formato YYYY-MM-DD.',
            'fecha2.date_format' => 'La Fecha 2 debe tener el formato YYYY-MM-DD.',
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
}
