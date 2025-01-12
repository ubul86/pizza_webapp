<?php

return [
    'signature' => env('GLIDE_SIGNATURE'),
    'source' => [
        'driver'   => 's3',  // Ez az AWS S3-t fogja használni
        'key'      => env('AWS_ACCESS_KEY_ID'),  // Az AWS hozzáférési kulcs
        'secret'   => env('AWS_SECRET_ACCESS_KEY'),  // Az AWS titkos kulcs
        'region'   => env('AWS_REGION'),  // AWS régió
        'bucket'   => env('AWS_BUCKET'),  // S3 bucket neve
        'url'      => env('AWS_S3_URL', ''),  // AWS S3 URL (ha más mint az alapértelmezett)
        'options'  => [],  // AWS S3 további opciói
    ],
    'cache' => [
        'driver' => 'local',  // Ez lehet bármi, ami nem AWS (javasolt `local`)
    ],
];
