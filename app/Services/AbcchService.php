<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

class AbcchService
{
    private const BASE_URL = 'https://abcch.com.br/sistema/api';

    public static function options($url, $postData)
    {
        $curlOptions = [
            CURLOPT_URL => self::BASE_URL . "/" . $url . "/&" . http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ];

        //dd($curlOptions);

        if (App::environment('local')) {
            $curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
            $curlOptions[CURLOPT_SSL_VERIFYHOST] = false;
        }

        return $curlOptions;
    }

    public static function get(string $route, array $options): array
    {
        $curlOptions = self::options($route . '/mixed', $options);

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

    public static function criador(array $options): array
    {
        return self::get('haras-list', $options);
    }

    public static function inspetor(array $options): array
    {
        return self::get('inspetor-list', $options);
    }
}
