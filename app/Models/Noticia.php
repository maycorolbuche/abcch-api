<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Noticia extends Model
{
    protected $table = 'tbl_site_noticia';
    protected $fillable = [
        'titulo',
        'imagem',
        'texto',
        'data_publicacao',
        'id_usuario_cad',
        'data_cadastro'
    ];

    protected $casts = [
        'data_publicacao' => 'date:Y-m-d',
        'data_cadastro' => 'date:Y-m-d',
    ];

    protected $appends  = ['data_publicacao_br', 'data_cadastro_br', 'imagem_url'];


    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where('data_excluido', 0)
                ->where('data_bloqueio', 0);
        });
    }


    public function getDataPublicacaoAttribute($value)
    {
        return Carbon::createFromFormat('Ymd', $value)->format('Y-m-d');
    }

    public function getDataCadastroAttribute($value)
    {
        return Carbon::createFromFormat('Ymd', $value)->format('Y-m-d');
    }

    public function getDataPublicacaoBrAttribute($value)
    {
        return Carbon::parse($this->data_publicacao)->format('d/m/Y');
    }

    public function getDataCadastroBrAttribute($value)
    {
        return Carbon::parse($this->data_cadastro)->format('d/m/Y');
    }

    public function getImagemUrlAttribute()
    {
        if (empty($this->imagem)) {
            return env('MEDIA_URL') . '/lib/img/logo_bh.png';
        }

        if (filter_var($this->imagem, FILTER_VALIDATE_URL)) {
            return $this->imagem;
        }

        return env('MEDIA_URL') . '/arq/noticia/' . $this->id . '/' . $this->imagem;
    }
}
