<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    protected $table = 'tbl_site_pagina';
    protected $fillable = [
        'menu',
        'submenu',
        'site_titulo',
        'site_url',
        'site_param',
        'site_janela',
        'site_html',
        'sequencia',
    ];

    public function getConteudoAttribute()
    {
        $texto = $this->texto;
        $texto = str_replace('src="/', 'src="' . env('MEDIA_URL') . '/arq/pagina/' . $this->id . '/', $texto);
        return $texto;
    }
}
