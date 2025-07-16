<?php

namespace App\Http\Controllers;

use App\Models\Estatistica;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EstatisticaController extends Controller
{
    public function index(Request $request)
    {
        $query = Estatistica::query()->get();

        dd($query->toArray());

        //dd($query->toSql(), $query->getBindings());

        return response()->json($noticias);
    }
}
