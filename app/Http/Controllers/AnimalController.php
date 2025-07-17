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
            if (isset($result["message"]) && $result["message"] <> "") {
                $result["error"] = $result["message"];
            } elseif (isset($result["result"])) {
                $result["data"] = $result["result"];
                unset($result["result"]);
                foreach ($result["data"] as $key => $item) {
                    $result["data"][$key]['DtFoaledBr'] = date("d/m/Y", strtotime($item["DtFoaled"]));
                    $result["data"][$key]['NmGender'] = ($item["CdGender"] == "M" ? "MACHO" : "FÊMEA");
                }
            }
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function types()
    {
        return self::listTypes();
    }

    private function listTypes()
    {
        return [
            ['id' => 1, 'text' => 'Animal'],
            ['id' => 2, 'text' => 'Garanhão'],
            ['id' => 3, 'text' => 'Égua'],
            ['id' => 4, 'text' => 'Pai'],
            ['id' => 5, 'text' => 'Mãe'],
            ['id' => 6, 'text' => 'Avô Paterno'],
            ['id' => 7, 'text' => 'Avô Materno'],
        ];
    }
}
