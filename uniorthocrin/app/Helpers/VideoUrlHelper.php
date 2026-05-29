<?php

namespace App\Helpers;

/**
 * Utilitário para lidar com URLs de vídeos externos (YouTube, Vimeo, etc.).
 *
 * Fornece:
 *  - conversão de URL "assistir" para URL embed
 *  - detecção do provedor
 *  - validação de domínios permitidos
 */
class VideoUrlHelper
{
    /**
     * Domínios externos permitidos para vídeos via URL.
     */
    public const ALLOWED_DOMAINS = [
        'youtube.com',
        'www.youtube.com',
        'youtu.be',
        'm.youtube.com',
        'vimeo.com',
        'www.vimeo.com',
        'player.vimeo.com',
    ];

    /**
     * Verifica se a URL é de um domínio de vídeo externo permitido.
     */
    public static function isAllowed(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $parsed = parse_url($url);
        $host   = strtolower($parsed['host'] ?? '');

        foreach (self::ALLOWED_DOMAINS as $domain) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Converte uma URL de vídeo externo para a URL de embed correspondente.
     * Retorna null se a URL não for reconhecida.
     */
    public static function toEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // YouTube: youtu.be/<id>
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_\-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // YouTube: youtube.com/watch?v=<id> ou /embed/<id> ou /shorts/<id>
        if (preg_match('/youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/)([a-zA-Z0-9_\-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Vimeo: vimeo.com/<id>  ou player.vimeo.com/video/<id>
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }

    /**
     * Retorna o provedor da URL ('youtube', 'vimeo', ou null).
     */
    public static function getProvider(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'youtube';
        }

        if (str_contains($url, 'vimeo.com')) {
            return 'vimeo';
        }

        return null;
    }

    /**
     * Retorna a thumbnail de preview de um vídeo externo (YouTube).
     * Para Vimeo exigiria chamada de API; retorna null.
     */
    public static function getThumbnailUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $videoId = null;

        if (preg_match('/youtu\.be\/([a-zA-Z0-9_\-]{11})/', $url, $m)) {
            $videoId = $m[1];
        } elseif (preg_match('/youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/)([a-zA-Z0-9_\-]{11})/', $url, $m)) {
            $videoId = $m[1];
        }

        return $videoId
            ? "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg"
            : null;
    }
}
