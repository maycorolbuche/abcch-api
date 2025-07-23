<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    public function index(Request $request, $tipo)
    {
        $limit = $request->input('limit', 30);
        $sortField = $request->input('sort_field', 'nome');
        $sortOrder = $request->input('sort_order', 'asc');
        $query = Pessoa::query()
            ->select([
                'id',
                'nome',
                'nome_fantasia',
                'nome_responsavel',
                'email',
                'celular',
                'telefone',
                'telefone2',
                'end_cep',
                'end_rua',
                'end_numero',
                'end_complemento',
                'end_bairro',
                'end_cidade',
                'end_uf',
                'end_pais',
            ])
            ->with(['perfis'])
            ->whereHas('perfis', function ($q) use ($tipo) {
                $q->where('tipo_acesso', Str::upper($tipo));
            });

        /* Busca */
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nome', 'like', "%{$search}%");
        }

        /* Ordenação */
        $query->orderBy($sortField, $sortOrder);
        $query->orderBy('id', $sortOrder);


        /* Paginação */
        $pessoas = $query->paginate($limit);

        //dd($query->toSql(), $query->getBindings());

        return response()->json($pessoas);
    }
}
