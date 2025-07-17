<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index(Request $request)
    {

        $cBuscaTipo = 1;
        $cBuscaNasc = 0;
        $cBusca = "CORDOBES";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cavalobh.com.br/api/interfacentsoft/ListAnimal',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                 CdFilterType: ' . $cBuscaTipo . '
                 , VlFoaledYear: ' . $cBuscaNasc . '
                 , NmAnimal: "' . $cBusca . '"
                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic TlRTT0ZUOkBiRCpCRTNqNVI5KUphPjt7aXhb'
            ),
        ));

        dd($curl);
        $response = curl_exec($curl);


        curl_close($curl);
        $data = json_decode($response, true);

        return $data;
    }
}
