<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para uploads de arquivos grandes
    |
    */

    'max_file_size' => 512000, // 500MB em KB
    'max_video_size' => 102400, // 100MB em KB
    'max_audio_size' => 512000, // 500MB em KB
    'max_document_size' => 512000, // 500MB em KB
    'max_image_size' => 10240, // 10MB em KB (imagens menores)
    
    'allowed_video_types' => [
        'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'm4v'
    ],
    
    'allowed_audio_types' => [
        'mp3', 'wav', 'flac', 'aac', 'ogg', 'wma', 'm4a'
    ],
    
    'allowed_document_types' => [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'
    ],
    
    'allowed_image_types' => [
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg', 'webp'
    ],
];
