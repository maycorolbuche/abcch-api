<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AnuncioController extends Controller
{
    public function index(Request $request, $tipo)
    {
        $anuncios = Anuncio::select(
            [
                'id',
                'tipo',
                'nome',
                'imagem',
                'site_url',
                'site_janela',
                'sequencia',
                'data_validade'
            ]
        )
            ->where('tipo', Str::upper($tipo))
            ->where(function ($q) {
                $q->where('data_validade', 0)
                    ->orWhereDate('data_validade', '>', Carbon::today());
            })
            ->get();


        return response()->json($anuncios);
    }
}
