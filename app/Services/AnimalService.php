<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

class AnimalService
{
    private const BASE_URL = 'https://cavalobh.com.br/api/interfacentsoft/ListAnimal';

    public static function get(int $type, int $year, string $name): array
    {
        $postData = json_encode([
            'CdFilterType' => $type,
            'VlFoaledYear' => $year,
            'NmAnimal'     => $name,
        ]);

        $curlOptions = [
            CURLOPT_URL => self::BASE_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . env('API_CAVALOBH_TOKEN', ''),
            ],
        ];

        if (App::environment('local')) {
            $curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
            $curlOptions[CURLOPT_SSL_VERIFYHOST] = false;
        }

        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new \Exception("Erro na requisição cURL: {$error}");
        }

        curl_close($curl);

        $data = json_decode($response, true);

        return $data ?? [];
    }
}
