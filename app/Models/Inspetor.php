<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Inspetor extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'BH09';
    protected $primaryKey = 'BH09Id';

    protected $fillable = [
        'BH09Nomeinspec',
        'BH09Telef1',
        'BH09Telef2',
        'BH09Celular',
        'BH09Email',
        'BH09Cid',
        'BH09Est',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where('BH09Ativo', 'S');
        });
    }
}
