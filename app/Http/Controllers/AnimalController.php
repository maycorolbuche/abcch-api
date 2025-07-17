<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimalService;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 1);
        $year = $request->input('year', 0);
        $name = $request->input('name', 'CORDO');

        try {
            $result = AnimalService::get(name: $name, type: $type, year: $year);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
