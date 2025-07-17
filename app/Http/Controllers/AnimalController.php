<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimalService;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('tipo', 1);
        $year = $request->input('ano', 0);
        $name = $request->input('nome', '');

        try {
            $result = AnimalService::get(name: $name, type: $type, year: $year);
            if ($result["message"]) {
                $result["error"] = $result["message"];
            }
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
