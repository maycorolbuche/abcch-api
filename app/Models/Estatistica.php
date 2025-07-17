<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estatistica extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'BH130';
    protected $primaryKey = 'BH130Id';

    protected $fillable = [
        'BH130Ano',
        'BH130Cod_Anim',
        'BH130Cod_AnimR',
        'BH130Nomeanimal',
        'BH130Pontuacao',
        'BH130Pontuacao2',
        'BH130QFilhos',
        'BH130Pontuacao3',
        'BH130QFilhos2',
        'BH130QFilhos3',
        'BH130Tipo',
        'BH130Ranking',
    ];
}
