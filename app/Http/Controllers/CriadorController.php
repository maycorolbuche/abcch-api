<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AbcchService;

class CriadorController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->input('name', $request->input('search', ''));
        $uf = $request->input('uf', '');
        $city = $request->input('city', '');

        try {
            $result["data"] = AbcchService::criador(['in_name' => $name, 'in_uf' => $uf, 'in_city' => $city]);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
