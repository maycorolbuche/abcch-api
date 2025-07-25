<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    protected $table = 'tbl_site_protocolo';
    public $timestamps = false;
    protected $fillable = [
        'origem',
        'protocolo',
        'texto',
        'id_objetivo',
        'id_direcionamento',
        'id_pessoa_sistema',
        'data_cadastro',
        'hora_cadastro',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->data_cadastro = date('Ymd');
            $model->hora_cadastro = date('H:i:s');
        });
    }
}
