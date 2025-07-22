<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Comunicado extends Model
{
    protected $table = 'tbl_site_comunicado';
    protected $fillable = [
        'id_pai',
        'tipo',
        'ano',
        'descricao',
        'site_url',
        'site_janela',
        'sequencia',
        'ind_ativo',
        'id_usuario_cad',
        'data_cadastro',
    ];

    protected $casts = [
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

    public function getDataCadastroBrAttribute($value)
    {
        return Carbon::parse($this->data_cadastro)->format('d/m/Y');
    }

    public function getUrlAttribute()
    {
        if ($this->site_url == '') {
            return null;
        }

        if (filter_var($this->site_url, FILTER_VALIDATE_URL)) {
            return $this->site_url;
        }

        return env('MEDIA_URL') . '/arq/comunicado/' . $this->site_url;
    }
}
