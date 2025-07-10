<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Noticia::query()->select([
            'id',
            'titulo',
            'imagem',
            'data_publicacao',
            'data_cadastro'
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
}
