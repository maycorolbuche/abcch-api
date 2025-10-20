<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index(Request $request)
    {
        $url = $request->input('url');

        // Adiciona hash anti-cache
        $hash = time(); // ou uniqid()
        $separator = (strpos($url, '?') === false) ? '?' : '&';
        $url .= $separator . '_v=' . $hash;

        // Define cabeçalhos para forçar recarregamento total
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Expires: 0');

        // Redireciona com 302 (força reload completo)
        header("Location: $url", true, 302);
        exit;
    }
}
