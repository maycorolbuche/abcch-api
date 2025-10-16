<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'tbl_site_menu';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_menu_pai',
        'id_sequencia',
        'nome',
        'url',
        'modulo',
        'parametro',
        'ind_ativo'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'id_menu_pai', 'id')
            ->where('ind_ativo', 'S')
            ->orderBy('id_sequencia');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'id_menu_pai', 'id');
    }
}
