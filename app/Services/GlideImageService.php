<?php

namespace App\Services;

class GlideImageService
{
    public static function getGlideImagePath(string $path, string $preset): string
    {
        $serverBaseUrl = env('IMAGE_BASE_URL', 'http://localhost:8090');
        return $serverBaseUrl . '/images/' . $path . '?' . $preset;
    }
}
