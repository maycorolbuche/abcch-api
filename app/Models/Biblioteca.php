<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Biblioteca extends Model
{
    protected $table = 'tbl_site_biblioteca';
    protected $fillable = [
        'tipo',
        'nome',
        'site_url',
        'site_janela',
        'sequencia',
        'datahora_validade',
        'id_usuario_cad',
        'data_cadastro'
    ];

    protected $casts = [
        'datahora_validade' => 'date:Y-m-d H:i:s',
        'data_cadastro' => 'date:Y-m-d',
    ];

    protected $appends  = [
        'data_cadastro_br',
        'url',
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

    public function getDatahoraValidadeAttribute($value)
    {
        if ($value == 0 || empty($value)) {
            return null;
        }

        return Carbon::createFromFormat('YmdHis', $value)->format('Y-m-d H:i:s');
    }

    public function getDataCadastroBrAttribute($value)
    {
        return Carbon::parse($this->data_cadastro)->format('d/m/Y');
    }

    public function getUrlAttribute()
    {
        if (filter_var($this->site_url, FILTER_VALIDATE_URL)) {
            return $this->site_url;
        }

        return env('MEDIA_URL') . '/arq/biblioteca/' . $this->site_url;
    }
}
