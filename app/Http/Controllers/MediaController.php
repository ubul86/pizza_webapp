<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Repositories\Interfaces\MediaRepositoryInterface;

class MediaController
{
    private MediaRepositoryInterface $mediaRepository;
    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function publicImage(Request $request, string $path): StreamedResponse
    {
        try {
//SignatureFactory::create(config('glide.signature'))->validateRequest($path, $_GET);
        } catch (SignatureException $e) {
            abort(404);
        }

        return $this->mediaRepository->getImage($path, $request->except('s'));
    }

    public function image(Request $request, string $path): StreamedResponse
    {
        return $this->mediaRepository->getImage($path, $request->input());
    }
}
