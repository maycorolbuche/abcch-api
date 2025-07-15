<?php

namespace App\Http\Controllers;

use App\Models\Pagina;

class PaginaController extends Controller
{

    public function show($menu, $submenu = null)
    {
        $pagina = Pagina::where('menu', $menu)->where('submenu', $submenu ?? '')->first();

        return response()->json($pagina);
    }
}
