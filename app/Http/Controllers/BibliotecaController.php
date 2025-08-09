<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BibliotecaController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 30);
        $search = $request->input('search');

        $query = Biblioteca::select(
            [
                'id',
                'tipo',
                'nome',
                'site_url',
                'site_janela',
                'sequencia',
                'datahora_validade',
                'data_cadastro',
            ]
        );

        $query->where(function ($q) {
            $q->where('datahora_validade', 0)
                ->orWhereDate('datahora_validade', '>', Carbon::today());
        });

        if (!empty($search)) {
            $query->where('nome', 'like', "%{$search}%");
        }

        $query->orderBy('sequencia');
        $query->orderBy('data_cadastro', 'desc');

        $biblioteca = $query->paginate($limit);


        return response()->json($biblioteca);
    }
}
