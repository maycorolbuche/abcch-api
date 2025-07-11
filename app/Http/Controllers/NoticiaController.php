<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Noticia::query()->select([
            'id',
            'titulo',
            'imagem',
            'data_publicacao',
            'data_cadastro',
            DB::raw("
                    CASE
                        WHEN LENGTH(REGEXP_REPLACE(texto, '<[^>]+>', '')) > 100 THEN
                            CONCAT(SUBSTRING(REGEXP_REPLACE(texto, '<[^>]+>', ''), 1, 100), '...')
                        ELSE
                            REGEXP_REPLACE(texto, '<[^>]+>', '')
                    END AS resumo
                ")
        ]);

        /* Outras condições */
        $query->whereDate('data_publicacao', '<=', Carbon::today());

        /* Busca */
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('titulo', 'like', "%{$search}%");
        }

        /* Ordenação */
        $sortField = $request->input('sort_field', 'data_publicacao');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);
        $query->orderBy('id', $sortOrder);


        /* Paginação */
        $limit = $request->input('limit', 30);
        $noticias = $query->paginate($limit);

        //dd($query->toSql(), $query->getBindings());

        return response()->json($noticias);
    }

    public function show($id)
    {
        $noticia = Noticia::find($id);

        return response()->json($noticia);
    }
}
