<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pessoa extends Model
{
    protected $table = 'tbl_adm_pessoa';
    protected $fillable = [
        'cpf_cnpj',
        'nome',
        'nome_fantasia',
        'nome_responsavel',
        'inscricao_estadual',
        'inscricao_municipal',
        'rg_numero',
        'rg_expedidor',
        'rg_data_emissao',
        'ind_sexo',
        'data_nascimento',
        'email',
        'celular',
        'telefone',
        'telefone2',
        'end_cep',
        'end_rua',
        'end_numero',
        'end_complemento',
        'end_bairro',
        'end_cidade',
        'end_uf',
        'end_pais',
    ];

    protected static function booted()
    {
        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->where((new static)->getTable() . '.data_excluido', 0);
        });
    }

    public function perfis()
    {
        return $this->belongsToMany(
            Perfil::class,
            PessoaPerfil::class,
            'id_pessoa',
            'id_perfil'
        );
    }
}
