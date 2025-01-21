<?php

namespace App\Strategies;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Strategies\Interfaces\ImageUploadStrategyInterface;

class LocalUploaderStrategy implements ImageUploadStrategyInterface
{
    public function upload(UploadedFile $file): string
    {
        if (!$path = Storage::disk('local')->putFile('product-images', $file)) {
            throw new \Exception('Error uploading file');
        }
        return $path;
    }

    public function delete(string $path): bool
    {
        return Storage::disk('local')->delete($path);
    }
}
