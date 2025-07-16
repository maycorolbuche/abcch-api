<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estatistica extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'BH01';
    protected $primaryKey = 'BH01Id';

    protected $fillable = [
        'BH01Progr',
        'BH01Descr',
        'BH01Permissao',
        'BH01Sistema',
        'BH01Rotina',
    ];
}
