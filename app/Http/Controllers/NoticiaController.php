<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 30);
        $sortField = $request->input('sort_field', 'data_publicacao');
        $sortOrder = $request->input('sort_order', 'desc');
        $search = $request->input('search');

        // Gera uma chave única de cache com base nos parâmetros
        $cacheKey = 'noticias:index:' . md5(json_encode([
            'limit' => $limit,
            'sort_field' => $sortField,
            'sort_order' => $sortOrder,
            'search' => $search,
            'resumo' => $limit <= 3,
            'page' => $request->input('page', 1),
        ]));


        // Retorna dados do cache ou executa a consulta
        $noticias = Cache::remember($cacheKey, 60, function () use ($limit, $sortField, $sortOrder, $search) {
            $select = [
                'id',
                'titulo',
                'imagem',
                'data_publicacao',
                'data_cadastro',
            ];

            if ($limit <= 3) {
                $select[] = DB::raw("
                    CONCAT(SUBSTRING(fn_strip_tags(texto), 1, 100), '...') AS resumo
                ");
            }

            $query = Noticia::query()->select($select);

            $query->whereDate('data_publicacao', '<=', Carbon::today());

            if (!empty($search)) {
                $query->where('titulo', 'like', "%{$search}%");
            }

            $query->orderBy($sortField, $sortOrder);
            $query->orderBy('id', $sortOrder);

            return $query->paginate($limit);
        });

        return response()->json($noticias);
    }

    public function show($id)
    {
        //$noticia = Cache::remember('noticia:' . $id, 3600, function () use ($id) {
        return Noticia::find($id);
        //});

        return response()->json($noticia);
    }
}
