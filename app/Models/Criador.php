<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Criador extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'haras';
    protected $primaryKey = 'harasid';

    protected $fillable = [
        'harasnome',
        'harassite',
        'harasuf',
        'harascidade',
        'harasaprov',
        'BH09Cid',
        'harasfoto',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where('harasexcluido', 'N')
                ->where('harasaprov', 'S');
        });
    }
}
