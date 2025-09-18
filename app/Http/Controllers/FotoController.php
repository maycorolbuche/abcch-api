<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;

class FotoController extends Controller
{
    public function index(Request $request)
    {
        $ano = $request->input('ano');

        $fotos = Foto::select(
            [
                'id',
                'nome',
                'sequencia',
                'ano',
            ]
        )
            ->where('ano', $ano)
            ->orderBy('sequencia')
            ->get();


        return response()->json($fotos);
    }
}
