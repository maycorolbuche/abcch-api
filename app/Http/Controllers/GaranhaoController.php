<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CavaloBhService;

class GaranhaoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        try {
            $result = CavaloBhService::stallions($search);

            if (isset($result["message"]) && $result["message"] <> "") {
                $result["error"] = $result["message"];
            } elseif (isset($result["result"])) {
                $result["data"] = $result["result"];
                unset($result["result"]);
                foreach ($result["data"] as $key => $item) {
                    $result["data"][$key]['DtFoaledBr'] = ($item["DtFoaled"] <> null ? date("d/m/Y", strtotime($item["DtFoaled"])) : null);
                    //$result["data"][$key]['NmGender'] = ($item["CdGender"] == "M" ? "MACHO" : "FÊMEA");
                }
            }
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
