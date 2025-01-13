<?php

namespace App\Strategies;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Strategies\Interfaces\ImageUploadStrategyInterface;

class LocalUploaderStrategy implements ImageUploadStrategyInterface
{
    public function upload(UploadedFile $file): string|bool
    {
        $path = Storage::disk('local')->putFile('product-images', $file);
        return Storage::url($path);
    }

    public function delete(string $path): bool
    {
        return Storage::disk('local')->delete($path);
    }
}
