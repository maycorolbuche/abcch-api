<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Perfil extends Model
{
    protected $table = 'tbl_adm_perfil';
    protected $fillable = [
        'nome',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where((new static)->getTable() . '.data_excluido', 0);
        });
    }
}
