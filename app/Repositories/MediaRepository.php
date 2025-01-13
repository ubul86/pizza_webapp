<?php

namespace App\Repositories;

use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Repositories\Interfaces\MediaRepositoryInterface;

class MediaRepository implements MediaRepositoryInterface
{
    private string $sourceDisk;
    private string $cacheDisk;
    private array $presets;
    public function __construct()
    {
        $this->sourceDisk = config('file.source_disk');
        $this->cacheDisk = config('file.cache_disk');
        $this->presets = config('presets');
    }

    /**
     * @inheritDoc
     */
    public function getImage(string $path, array $options = []): StreamedResponse
    {
        $server = ServerFactory::create([
            'driver' => 'imagick',
            'source' => Storage::disk($this->sourceDisk)->getDriver(),
            'cache' => Storage::disk($this->cacheDisk)->getDriver(),
            'response' => new LaravelResponseFactory(app('request')),
            'presets' => $this->presets,
            //'watermarks' => new Filesystem(new Local(public_path())),
        ]);
        return $server->getImageResponse($path, $options);
    }
}
