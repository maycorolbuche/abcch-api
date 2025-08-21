<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Pagina;
use App\Models\Comunicado;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\CavaloBhService;


class PesquisaController extends Controller
{
    public function index(Request $request, $search)
    {
        $limit = 100;
        $search_like = '%' . $search . '%';

        $noticias = Noticia::select(['id', 'titulo', 'data_publicacao', 'data_cadastro', 'imagem'])
            ->where('titulo', 'like', $search_like)
            ->whereDate('data_publicacao', '<=', Carbon::today())
            ->orderBy('data_publicacao', 'desc')
            ->limit($limit)
            ->get();

        $animais = CavaloBhService::list(name: $search, type: 1, year: 0);
        $animais = $animais['result'] ?? null;

        $paginas = Pagina::select(['id', 'menu', 'submenu', 'site_titulo', 'site_url', 'site_param'])
            ->where('site_html', 'like', $search_like)
            ->orWhere('site_titulo', 'like', $search_like)
            ->orderBy('site_titulo')
            ->limit($limit)
            ->get();

        $comunicados = Comunicado::select()
            ->where('descricao', 'like', $search_like)
            ->orderBy('tipo')
            ->orderBy('ano', 'desc')
            ->orderBy('id_pai')
            ->limit($limit)
            ->get();

        return response()->json([
            'noticias' => $noticias,
            'animais' => $animais,
            'paginas' => $paginas,
            'comunicados' => $comunicados,
        ]);
    }
}
