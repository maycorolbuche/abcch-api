<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class CavaloBhService
{
    private const BASE_URL = 'https://cavalobh.com.br/api/interfacentsoft';

    public static function post(string $route, array $payload): array
    {
        $url = self::BASE_URL . '/' . $route;

        $http = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Basic ' . env('API_CAVALOBH_TOKEN', ''),
                'Content-Type'  => 'application/json',
            ])
            ->asJson();

        if (App::environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($url, $payload);

        if ($response->failed()) {
            throw new \Exception(
                'Erro HTTP ' . $response->status() . ': ' . $response->body()
            );
        }

        return $response->json() ?? [];
    }

    public static function list(int $type, int $year, string $name): array
    {
        return self::post('ListAnimal', [
            'CdFilterType' => $type,
            'VlFoaledYear' => $year,
            'NmAnimal'     => $name,
        ]);
    }

    public static function getAnimal(string $id): array
    {
        return self::post('GetAnimal', [
            'CdToken' => $id
        ]);
    }

    public static function getPedigree(string $id, int $generation = 4): array
    {
        return self::post('GetPedigree', [
            'CdToken' => $id,
            'VlGeneration' => $generation,
        ]);
    }

    public static function goldMares(string $type): array
    {
        return self::post('ListGoldMaresType', [
            'CdGoldMaresType' => strtoupper($type),
        ]);
    }

    public static function stallions(string $search, string $initial): array
    {
        return self::post('ListApprovedStallions', [
            'NmAnimal' => strtoupper($search),
            'NmAnimalStartsWith' => strtoupper($initial),
        ]);
    }
}
