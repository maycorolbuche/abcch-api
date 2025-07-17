<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'BH13';
    protected $primaryKey = 'BH13Id';

    protected $fillable = [
        'BH13_BH04Empr',
        'BH13Nr_Interno',
        'BH13Cod_AnimR',
        'BH13Cod_Anim',
        'BH13Nome',
        'BH13LocNasc',
        'BH13Cod_Propr',
        'BH13Cod_Gar1',
        'BH13Cod_Gar2',
        'BH13Dtaprov',
        'BH13Dt_Nasc',
        'BH13Sexo',
        'BH13Altura',
        'BH13Cod_Pelag',
        'BH13TE',
        'BH13IA',
        'BH13Imp',
        'BH13Dt_Imp',
        'BH13Exp',
        'BH13Tp_Reg',
        'BH13Dt_RegP',
        'BH13Dt_RegD',
        'BH13Vias',
        'BH13Raca_IA',
        'BH13Dt_Morte',
        'BH13Numero_Prot',
        'BH13Criad_CodProp',
        'BH13Criad_CodHaras',
        'BH13Tipagem',
        'BH13Dt_Insp',
        'BH13Cod_Insp',
        'BH13Obs',
        'BH13Corpo',
        'BH13Marcafogo',
        'BH13AELF',
        'BH13ADRF',
        'BH13PELH',
        'BH13PDRH',
        'BH13Cabeca',
        'BH13Microchip',
        'BH13DNA',
        'BH13Libsite',
        'BH13Inadimplente',
        'BH13Qualif',
        'BH13_KS120Id',
        'BH13Apelido',
        'BH13NCob',
        'BH13Categ',
        'BH13Selo',
        'BH13Foto',
        'BH13Anoaprov',
        'B13Obssite',
        'BH13Hipomundo',
        'BH13Regoriginal',
        'BH13Racaoriginal',
        'BH13Gsangue',
        'BH13DtReservaDominio',
        'BH13VendaContato',
        'BH13VendaObs',
    ];
}
