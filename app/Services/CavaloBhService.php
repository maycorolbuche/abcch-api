<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

class CavaloBhService
{
    private const BASE_URL = 'https://cavalobh.com.br/api/interfacentsoft';

    public static function options($url, $postData)
    {
        $curlOptions = [
            CURLOPT_URL => self::BASE_URL . "/" . $url,
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

        return $curlOptions;
    }

    public static function list(int $type, int $year, string $name): array
    {
        $postData = json_encode([
            'CdFilterType' => $type,
            'VlFoaledYear' => $year,
            'NmAnimal'     => $name,
        ]);

        $curlOptions = self::options('ListAnimal', $postData);

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

    public static function get(string $id): array
    {
        $postData = json_encode([
            'CdToken' => $id
        ]);

        $curlOptions = self::options('GetAnimal', $postData);

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

    public static function getPedigree(string $id, int $generation = 4): array
    {
        $postData = json_encode([
            'CdToken' => $id,
            'VlGeneration' => $generation,
        ]);

        $curlOptions = self::options('GetPedigree', $postData);

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

    public static function goldMares(string $type): array
    {
        $postData = json_encode([
            'CdGoldMaresType' => strtoupper($type),
        ]);

        $curlOptions = self::options('ListGoldMaresType', $postData);

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
