<?php

namespace App\Factories;

use App\Strategies\Interfaces\ImageUploadStrategyInterface;
use App\Strategies\S3UploaderStrategy;
use App\Strategies\LocalUploaderStrategy;

class ImageUploaderFactory
{
    public static function createUploader(): ImageUploadStrategyInterface
    {
        $uploadType = config('app.image_upload_to');

        return match ($uploadType) {
            's3' => new S3UploaderStrategy(),
            'local' => new LocalUploaderStrategy(),
            default => throw new \Exception('Invalid upload strategy'),
        };
    }
}
