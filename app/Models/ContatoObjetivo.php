<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContatoObjetivo extends Model
{
    protected $table = 'tbl_site_contato_objetivo';
    protected $fillable = [
        'nome',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where('data_excluido', 0);
        });
    }
}
