<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Inscrito;
use Illuminate\Http\Request;

class AreaController extends Controller{
    function index(){
        return Area::all();
    }
    public function store(Request $request)
    {
        $area = Area::findOrFail($request->area_id);

        // Validar campos base
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'grupo_nombre' => 'nullable|string|max:255',
            'integrantes' => 'required|array|min:1|max:' . ($area->max_integrantes ?? 10),
            'integrantes.*.nombre' => 'required|string|max:255',
            'integrantes.*.ci' => 'required|string|max:20',
            'integrantes.*.tutor' => 'nullable|string|max:255',
            'integrantes.*.telefono' => 'nullable|string|max:20',
            'integrantes.*.curso' => 'required|string|max:20',
        ]);

        // Guardar en la tabla inscritos
        $inscrito = new Inscrito();
        $inscrito->area_id = $validated['area_id'];
        $inscrito->grupo_nombre = $validated['grupo_nombre'] ?? null;
        $inscrito->max_integrantes = $area->max_integrantes ?? 10;

        // Mapear integrantes
        foreach ($validated['integrantes'] as $index => $data) {
            $pos = $index + 1;
            $inscrito->{"estudiante{$pos}"} = $data['nombre'];
            $inscrito->{"ci{$pos}"} = $data['ci'];
            $inscrito->{"tutor{$pos}"} = $data['tutor'] ?? null;
            $inscrito->{"telefono{$pos}"} = $data['telefono'] ?? null;
            $inscrito->{"curso{$pos}"} = $data['curso'];
        }

        $inscrito->save();

        return response()->json([
            'message' => 'Inscripción registrada correctamente',
            'data' => $inscrito
        ], 201);
    }
}
