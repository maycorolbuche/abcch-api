<?php

namespace App\Http\Controllers;

use App\Models\Criador;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CriadorController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 30);

        $query = Criador::query()
            ->select([
                'harasid as id',
                'harasnome as nome',
                'harascidade as end_cidade',
                'harasuf as end_uf',
                'harassite as site',
            ]);


        /* Busca */
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('harasnome', 'like', "%{$search}%");
        }
        if ($request->has('uf')) {
            $search = $request->input('uf');
            $query->where('harasuf', Str::upper($search));
        }

        /* Ordenação */
        $query->orderBy("harasnome", "asc");


        /* Paginação */
        $pessoas = $query->paginate($limit);

        //dd($query->toSql(), $query->getBindings());

        return response()->json($pessoas);
    }
}
