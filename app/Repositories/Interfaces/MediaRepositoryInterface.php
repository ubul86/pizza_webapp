<?php

namespace App\Repositories\Interfaces;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface MediaRepositoryInterface
{
    /**
     * @param string $path
     * @param array $options
     * @return StreamedResponse
     */
    public function getImage(string $path, array $options): StreamedResponse;
}
