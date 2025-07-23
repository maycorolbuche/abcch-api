<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PessoaPerfil extends Model
{
    protected $table = 'tbl_adm_pessoa_perfil';
    protected $fillable = [
        'id_pessoa',
        'id_perfil',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where((new static)->getTable() . '.data_excluido', 0);
        });
    }
}
