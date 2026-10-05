<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        if (
            $request->is('animais/*/print') ||
            $request->is('animal/*/print')
        ) {
            return $next($request);
        }


        $allowedOrigins = array_map(
            fn($origin) => $this->normalizeOrigin($origin),
            explode(',', env('CORS_ALLOWED_ORIGINS', '*'))
        );

        $origin = $request->headers->get('Origin');
        $normalizedOrigin = $this->normalizeOrigin($origin);

        if (
            in_array('*', $allowedOrigins, true) ||
            in_array($normalizedOrigin, $allowedOrigins, true)
        ) {
            $headers = [
                'Access-Control-Allow-Origin' => $origin,
                'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age' => '86400',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
            ];
        } else {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }

        if ($request->isMethod('OPTIONS')) {
            return response()->json(
                ['method' => 'OPTIONS'],
                200,
                $headers
            );
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }


        $response->headers->set('X-Debug-Origin', $origin ?? 'NULL');
        $response->headers->set('X-Debug-Allowed-Origins', implode(',', $allowedOrigins));
        $response->headers->set('X-Debug-Normalized-Origins', $normalizedOrigin);

        return $response;
    }

    private function normalizeOrigin(?string $origin): string
    {
        if (!$origin) {
            return '';
        }

        return strtolower(
            preg_replace('#^https?://#i', '', rtrim(trim($origin), '/'))
        );
    }
}
