<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class AbcchService
{
    private const BASE_URL = 'https://sistema.abcch.com.br/api';

    public static function get(string $route, array $options): array
    {
        $url = self::BASE_URL . '/' . $route . '/mixed';
        $http = Http::timeout(30);
        if (App::environment('local')) {
            $http->withoutVerifying();
        }
        $response = $http->get($url, $options);

        if ($response->failed()) {
            throw new \Exception('Erro na requisição HTTP');
        }

        return $response->json() ?? [];
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
