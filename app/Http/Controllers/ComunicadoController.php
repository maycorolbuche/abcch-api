<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ComunicadoController extends Controller
{
    public function index(Request $request, $tipo)
    {
        $comunicados = Comunicado::select([
            'id',
            'id_pai',
            'tipo',
            'ano',
            'descricao',
            'site_url',
            'site_janela',
            'sequencia',
            'ind_ativo',
            'id_usuario_cad',
            'data_cadastro',
        ])
            ->where('tipo', Str::upper($tipo))
            ->where('ind_ativo', 'S')
            ->orderBy('sequencia')
            ->orderBy('descricao');

        if ($request->has('ano')) {
            $ano = $request->input('ano');
            $comunicados->where('ano', $ano);
        }

        $comunicados = $comunicados->get();

        // Função recursiva para montar a árvore de comunicados
        function buildTree($items, $parentId = 0)
        {
            $branch = [];
            foreach ($items as $item) {
                if ($item->id_pai == $parentId) {
                    $children = buildTree($items, $item->id);
                    if ($children) {
                        $item->items = $children;
                    }
                    $branch[] = $item;
                }
            }
            return $branch;
        }

        $tree = buildTree($comunicados);

        return response()->json($tree);
    }
}
