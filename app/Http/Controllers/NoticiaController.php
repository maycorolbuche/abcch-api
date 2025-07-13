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
        $select = [
            'id',
            'titulo',
            'imagem',
            'data_publicacao',
            'data_cadastro',
        ];

        $limit = $request->input('limit', 30);
        $sortField = $request->input('sort_field', 'data_publicacao');
        $sortOrder = $request->input('sort_order', 'desc');


        if ($limit <= 3) {
            $select[] = DB::raw("
                CONCAT(SUBSTRING(fn_strip_tags(texto), 1, 100), '...') AS resumo
            ");
        }

        $query = Noticia::query()->select($select);

        /* Outras condições */
        $query->whereDate('data_publicacao', '<=', Carbon::today());

        /* Busca */
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('titulo', 'like', "%{$search}%");
        }

        /* Ordenação */
        $query->orderBy($sortField, $sortOrder);
        $query->orderBy('id', $sortOrder);


        /* Paginação */
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
