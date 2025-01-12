<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponseMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData();

            if ($response->status() >= 200 && $response->status() < 300) {
                return response()->json([
                    'success' => true,
                    'message' => $data->message ?? 'Request successful',
                    'data' => $data ?? null,
                ], $response->status());
            }

            return response()->json([
                'success' => false,
                'message' => $data->message ?? 'An error occurred',
                'errors' => $data->errors ?? null,
            ], $response->status());
        }

        return $response;
    }
}
