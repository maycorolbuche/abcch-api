<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Anuncio extends Model
{
    protected $table = 'tbl_site_anuncio';
    protected $fillable = [
        'tipo',
        'nome',
        'imagem',
        'site_url',
        'site_janela',
        'local',
        'sequencia',
        'data_validade',
        'id_usuario_cad',
        'data_cadastro'
    ];

    protected $casts = [
        'data_validade' => 'date:Y-m-d',
        'data_cadastro' => 'date:Y-m-d',
    ];

    protected $appends  = [
        'imagem_url',
    ];


    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where('data_excluido', 0);
        });
    }

    public function getDataCadastroAttribute($value)
    {
        if ($value == 0 || empty($value)) {
            return null;
        }

        return Carbon::createFromFormat('Ymd', $value)->format('Y-m-d');
    }

    public function getDataValidadeAttribute($value)
    {
        if ($value == 0 || empty($value)) {
            return null;
        }

        return Carbon::createFromFormat('Ymd', $value)->format('Y-m-d');
    }

    public function getImagemUrlAttribute()
    {
        if (filter_var($this->imagem, FILTER_VALIDATE_URL)) {
            return $this->imagem;
        }

        return env('MEDIA_URL') . '/arq/anuncio/' . $this->imagem;
    }
}
