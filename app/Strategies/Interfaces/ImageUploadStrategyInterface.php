<?php

namespace App\Strategies\Interfaces;

use Illuminate\Http\UploadedFile;

interface ImageUploadStrategyInterface
{
    public function upload(UploadedFile $file): string|bool;
    public function delete(string $path): bool;
}
