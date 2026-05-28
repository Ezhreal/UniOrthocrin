<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizationService
{
    /**
     * Optimize an image file on the filesystem and return generated paths.
     *
     * @param string $path
     * @param bool $force
     * @return array|null
     */
    public function optimizePath(string $path, bool $force = false): ?array
    {
        if (!config('image_optimization.enabled', true)) {
            Log::info("Image optimization is disabled in config.");
            return null;
        }

        $disk = Storage::disk('private');

        if (!$disk->exists($path)) {
            Log::warning("File does not exist for optimization: {$path}");
            return null;
        }

        $fullOriginalPath = $disk->path($path);
        
        // Check extension
        $pathInfo = pathinfo($path);
        $extension = strtolower($pathInfo['extension'] ?? '');
        $allowedExtensions = config('image_optimization.allowed_extensions', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
        
        if (!in_array($extension, $allowedExtensions)) {
            Log::info("Extension not allowed for optimization: {$extension}");
            return null;
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullOriginalPath);
            
            $dirname = ($pathInfo['dirname'] ?? '') === '.' ? '' : ($pathInfo['dirname'] ?? '');
            $dirname = $dirname ? rtrim($dirname, '/') . '/' : '';
            $filename = $pathInfo['filename'];
            $quality = config('image_optimization.quality', 80);

            // 1. Version optimized
            $optimizedImage = clone $image;
            $encodedOptimized = $optimizedImage->toWebp($quality);
            $optimizedPath = $dirname . $filename . '_optimized.webp';
            $disk->put($optimizedPath, $encodedOptimized->toString());

            // 2. Thumbnails
            $sizes = config('image_optimization.sizes', ['sm' => 320, 'md' => 640, 'lg' => 1024]);
            $thumbPaths = [];

            foreach ($sizes as $key => $width) {
                $thumbImage = clone $image;
                if ($thumbImage->width() > $width) {
                    $thumbImage->scale(width: $width);
                }
                $encodedThumb = $thumbImage->toWebp($quality);
                $thumbPath = $dirname . $filename . '_thumb_' . $key . '.webp';
                $disk->put($thumbPath, $encodedThumb->toString());
                $thumbPaths[$key] = $thumbPath;
            }

            return [
                'optimized_path' => $optimizedPath,
                'thumbnail_sm_path' => $thumbPaths['sm'] ?? null,
                'thumbnail_md_path' => $thumbPaths['md'] ?? null,
                'thumbnail_lg_path' => $thumbPaths['lg'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error("Failed to optimize image: {$path}. Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Optimize a File model.
     *
     * @param File $file
     * @param bool $force
     * @return bool
     */
    public function optimizeFile(File $file, bool $force = false): bool
    {
        if ($file->type !== 'image') {
            return false;
        }

        if ($file->is_optimized && !$force) {
            return false;
        }

        $paths = $this->optimizePath($file->path, $force);

        if ($paths) {
            $file->optimized_path = $paths['optimized_path'];
            $file->thumbnail_sm_path = $paths['thumbnail_sm_path'];
            $file->thumbnail_md_path = $paths['thumbnail_md_path'];
            $file->thumbnail_lg_path = $paths['thumbnail_lg_path'];
            $file->is_optimized = true;
            return $file->save();
        }

        return false;
    }

    /**
     * Optimize an image file on the filesystem in place (converting it to WebP).
     *
     * @param string $path
     * @return string|null The new path or the original if skipped/failed.
     */
    public function optimizePathInPlace(string $path): ?string
    {
        if (!config('image_optimization.enabled', true)) {
            return $path;
        }

        $disk = Storage::disk('private');

        if (!$disk->exists($path)) {
            return $path;
        }

        // Check if already WebP
        $pathInfo = pathinfo($path);
        $extension = strtolower($pathInfo['extension'] ?? '');
        
        if ($extension === 'webp') {
            return $path;
        }

        $allowedExtensions = config('image_optimization.allowed_extensions', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
        
        if (!in_array($extension, $allowedExtensions)) {
            return $path;
        }

        try {
            $fullOriginalPath = $disk->path($path);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullOriginalPath);
            
            $dirname = ($pathInfo['dirname'] ?? '') === '.' ? '' : ($pathInfo['dirname'] ?? '');
            $dirname = $dirname ? rtrim($dirname, '/') . '/' : '';
            $filename = $pathInfo['filename'];
            $quality = config('image_optimization.quality', 80);

            // Encode to WebP
            $encoded = $image->toWebp($quality);
            $newPath = $dirname . $filename . '.webp';
            
            // Put WebP
            $disk->put($newPath, $encoded->toString());
            
            // Delete original
            $disk->delete($path);

            return $newPath;
        } catch (\Exception $e) {
            Log::error("Failed to optimize image in place: {$path}. Error: " . $e->getMessage());
            return $path;
        }
    }
}
