<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $path;
    protected $type;
    protected $preset;

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = (int) $type;
        return $this;
    }

    public function setPreset(string $preset): self
    {
        $this->preset = $preset;
        return $this;
    }

    public function build(): string
    {
        return match ($this->type) {
            Image::TYPE_STORAGE => Storage::url($this->path),
            Image::TYPE_PUBLIC => asset($this->path),
            Image::TYPE_S3 => GlideImageService::getGlideImagePath($this->path, "p={$this->preset}"),
            default => throw new \Exception("Unsupported image type: {$this->type}"),
        };
    }
}
