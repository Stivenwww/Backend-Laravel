<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MunicipiosControllerApi extends Controller
{
    public function obtenerMunicipios()
    {
        $municipios = DB::select('CALL ObtenerMunicipios()');
        return response()->json($municipios);
    }

    public function obtenerMunicipioPorId($id)
    {
        $municipio = DB::select('CALL ObtenerMunicipioPorId(?)', [$id]);

        if (!empty($municipio)) {
            return response()->json([
                'mensaje' => 'Municipio encontrado',
                'datos' => $municipio[0]
            ], 200);
        } else {
            return response()->json([
                'mensaje' => 'Municipio no encontrado',
            ], 404);
        }
    }

    public function insertarMunicipio(Request $request)
    {
        DB::statement('CALL InsertarMunicipio(?, ?, ?)', [
            $request->nombre,
            $request->codigo,
            $request->departamento_id
        ]);

        return response()->json(['mensaje' => 'Municipio insertado correctamente'], 201);
    }

    public function actualizarMunicipio(Request $request, $id)
    {
        DB::statement('CALL ActualizarMunicipio(?, ?, ?, ?)', [
            $id,
            $request->nombre,
            $request->codigo,
            $request->departamento_id
        ]);

        return response()->json(['mensaje' => 'Municipio actualizado correctamente'], 200);
    }

    public function eliminarMunicipio($id)
    {
        DB::statement('CALL EliminarMunicipio(?)', [$id]);

        return response()->json(['mensaje' => 'Municipio eliminado correctamente'], 200);
    }
}