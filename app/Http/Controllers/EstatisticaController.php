<?php

namespace App\Http\Controllers;

use App\Models\Estatistica;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstatisticaController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 30);

        $fil_nome = $request->input('nome') ?? '';
        $fil_ano = $request->input('ano') ?? '';
        $fil_tipo = $request->input('tipo') ?? '';

        $query = Estatistica::query()->select([
            'a.BH130Nomeanimal as animal_nome',
            DB::raw("a.BH130Cod_Anim + '-' + BH130Cod_AnimR as animal_registro"),
            'b.BH13Nr_Interno as animal_id',
            DB::raw("case a.BH130Ano when '0000' then 'Acumulado' else a.BH130Ano end as ano"),
            'a.BH130Tipo as tipo',
            'a.BH130Ranking as classificacao',
            DB::raw("CONVERT(decimal(20,2), ROUND(a.BH130Pontuacao2/100,2)) as pontuacao"),
            DB::raw("(SELECT COUNT(*) FROM BH130 a0
                WHERE a0.BH130Tipo = '$fil_tipo' AND a0.BH130Ano = '$fil_ano'
                AND ('$fil_nome'='' OR ('$fil_nome' <> '' AND a0.BH130Nomeanimal LIKE '%$fil_nome%'))) AS num_registro"),
        ])
            ->from((new Estatistica)->getTable() . ' as a')
            ->leftJoin((new Animal)->getTable() . ' as b', function ($join) {
                $join->on('b.BH13Cod_Anim', '=', 'a.BH130Cod_Anim')
                    ->on('b.BH13Cod_AnimR', '=', 'a.BH130Cod_AnimR');
            });


        if ($request->has('nome')) {
            $query->where('a.BH130Nomeanimal', 'like', "%{$fil_nome}%");
        }
        if ($request->has('ano')) {
            $query->where('a.BH130Ano', str_pad($fil_ano, 4, '0', STR_PAD_LEFT));
        }
        if ($request->has('tipo')) {
            $query->where('a.BH130Tipo', $fil_tipo);
        }

        $query->orderBy('a.BH130Ranking');


        $estatisticas = $query->paginate($limit);

        $estatisticas->getCollection()->transform(function ($item) {
            $item->tipo_descricao = $this->getType($item->tipo);
            return $item;
        });

        return response()->json($estatisticas);
    }


    public function types()
    {
        return self::listTypes();
    }

    private function getType($id)
    {
        $types = $this->listTypes();
        $result = array_filter($types, function ($type) use ($id) {
            return $type['id'] == $id;
        });

        return !empty($result) ? reset($result)['value'] : null;
    }

    private function listTypes()
    {
        return [
            ['id' => 0, 'value' => 'Cavalo Atleta - Pontos Corridos'],
            ['id' => 1, 'value' => 'Mãe do Cavalo Atleta - Pontos Corridos'],
            ['id' => 2, 'value' => 'Pai do Cavalo Atleta - Pontos Corridos'],
            ['id' => 3, 'value' => 'Criador do Cavalo Atleta - Pontos Corridos'],
            ['id' => 4, 'value' => 'Mãe do Cavalo Atleta - Proporcional dos Filhos maiores de 4 Anos'],
            ['id' => 5, 'value' => 'Pai do Cavalo Atleta - Proporcional dos Filhos maiores de 4 anos'],
            ['id' => 6, 'value' => 'Mãe do Cavalo Atleta - Proporcional Filhos Lançados'],
            ['id' => 7, 'value' => 'Pai do Cavalo Atleta - Proporcional Filhos Lançados'],
            ['id' => 8, 'value' => 'Criador do Cavalo Atleta - Proporcional dos Filhos maiores de 4 Anos'],
            ['id' => 9, 'value' => 'Criador do Cavalo Atleta - Proporcional Filhos Lançados']
        ];
    }
}
