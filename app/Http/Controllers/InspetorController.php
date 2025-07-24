<?php

namespace App\Http\Controllers;

use App\Models\Inspetor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspetorController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 30);

        $query = Inspetor::query()
            ->select([
                'BH09Id as id',
                'BH09Nomeinspec as nome',
                'BH09Email as email',
                'BH09Celular as celular',
                'BH09Telef1 as telefone',
                'BH09Telef2 as telefone2',
                'BH09Cid as end_cidade',
                'BH09Est as end_uf',
            ]);

        $query->where(DB::raw('ISNULL(BH09EstoqueICSI, \'\')'), '<>', 'S');

        /* Busca */
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('BH09Nomeinspec', 'like', "%{$search}%");
        }
        if ($request->has('uf')) {
            $search = $request->input('uf');
            $query->where('BH09Est', Str::upper($search));
        }

        /* Ordenação */
        $query->orderBy("BH09Nomeinspec", "asc");


        /* Paginação */
        $pessoas = $query->paginate($limit);

        //dd($query->toSql(), $query->getBindings());

        return response()->json($pessoas);
    }
}
