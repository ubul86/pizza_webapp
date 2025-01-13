<?php

namespace App\Strategies;

use App\Strategies\Interfaces\ImageUploadStrategyInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class S3UploaderStrategy implements ImageUploadStrategyInterface
{
    public function upload(UploadedFile $file): string|bool
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        return Storage::disk('s3')->putFileAs('product-images', $file, $filename);
    }

    public function delete(string $path): bool
    {
        return Storage::disk('s3')->delete($path);
    }
}
