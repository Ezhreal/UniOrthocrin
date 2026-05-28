<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the settings for the automatic image optimization
    | service using Intervention Image v3.
    |
    */

    'enabled' => env('IMAGE_OPTIMIZATION_ENABLED', true),

    'quality' => env('IMAGE_OPTIMIZATION_QUALITY', 80),

    'format' => 'webp', // Format to convert images to

    'sizes' => [
        'sm' => env('IMAGE_THUMBNAIL_SM', 320),
        'md' => env('IMAGE_THUMBNAIL_MD', 640),
        'lg' => env('IMAGE_THUMBNAIL_LG', 1024),
    ],

    'allowed_extensions' => [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'
    ],
];
