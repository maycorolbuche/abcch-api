<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Foto extends Model
{
    protected $table = 'tbl_site_foto';
    protected $fillable = [
        'nome',
        'sequencia',
        'ano',
    ];

    protected $appends  = [
        'url',
    ];


    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where('data_excluido', 0);
        });
    }

    public function getUrlAttribute()
    {
        if (filter_var($this->nome, FILTER_VALIDATE_URL)) {
            return $this->nome;
        }

        return env('MEDIA_URL') . '/arq/fotos/' . $this->ano . '/' . $this->nome;
    }
}
