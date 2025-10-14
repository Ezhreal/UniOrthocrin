<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Format file size in human readable format
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Get file type icon class
     */
    public static function getFileTypeIcon($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'fas fa-image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'fas fa-video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'fas fa-music';
        } elseif (str_starts_with($mimeType, 'application/pdf')) {
            return 'fas fa-file-pdf';
        } elseif (str_starts_with($mimeType, 'application/zip') || str_starts_with($mimeType, 'application/x-zip-compressed')) {
            return 'fas fa-file-archive';
        } elseif (str_starts_with($mimeType, 'text/')) {
            return 'fas fa-file-alt';
        } else {
            return 'fas fa-file';
        }
    }

    /**
     * Get content type label
     */
    public static function getContentTypeLabel($type)
    {
        return match($type) {
            'posts' => 'Posts',
            'folders' => 'Pastas',
            'videos' => 'Vídeos',
            'miscellaneous' => 'Diversos',
            default => ucfirst($type)
        };
    }

    /**
     * Get content type icon
     */
    public static function getContentTypeIcon($type)
    {
        return match($type) {
            'posts' => 'fas fa-newspaper',
            'folders' => 'fas fa-folder',
            'videos' => 'fas fa-video',
            'miscellaneous' => 'fas fa-ellipsis-h',
            default => 'fas fa-file'
        };
    }

    /**
     * Get content type color class
     */
    public static function getContentTypeColor($type)
    {
        return match($type) {
            'posts' => 'text-blue-600',
            'folders' => 'text-green-600',
            'videos' => 'text-red-600',
            'miscellaneous' => 'text-purple-600',
            default => 'text-gray-600'
        };
    }

    /**
     * Get content type background color class
     */
    public static function getContentTypeBgColor($type)
    {
        return match($type) {
            'posts' => 'bg-blue-100',
            'folders' => 'bg-green-100',
            'videos' => 'bg-red-100',
            'miscellaneous' => 'bg-purple-100',
            default => 'bg-gray-100'
        };
    }
}
