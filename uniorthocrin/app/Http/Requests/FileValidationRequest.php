<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Validações específicas para Produtos
     */
    public static function getProductValidationRules()
    {
        return [
            'gallery_images' => 'nullable|array',
            // Imagens da galeria de produtos: até 500MB (mesmo limite dos vídeos)
            'gallery_images.*' => 'image|mimes:jpeg,jpg,png,webp|max:' . config('upload.max_video_size'),
            'gallery_videos' => 'nullable|array', 
            'gallery_videos.*' => 'file|mimes:mp4,mov,ogg|max:' . config('upload.max_video_size'), // 500MB
        ];
    }

    /**
     * Validações específicas para Biblioteca
     */
    public static function getLibraryValidationRules()
    {
        return [
            'files' => 'nullable|array',
            // Permitir qualquer tipo de arquivo (inclusive vídeos), limitando apenas o tamanho
            'files.*' => 'file|max:512000', // 500MB
        ];
    }

    /**
     * Validações específicas para News
     */
    public static function getNewsValidationRules()
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240', // 10MB
        ];
    }

    /**
     * Validações específicas para Na Mídia (Media)
     */
    public static function getMediaValidationRules()
    {
        return [
            'files' => 'nullable|array',
            // Permitir qualquer tipo de arquivo, limitando apenas o tamanho (1GB)
            'files.*' => 'file|max:1024000', 
        ];
    }

    /**
     * Validações específicas para Treinamento
     */
    public static function getTrainingValidationRules()
    {
        return [
            'videos' => 'nullable|array',
            'videos.*' => 'file|mimes:mp4,mov,ogg,avi|max:' . config('upload.max_video_size'), // 500MB
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:512000', // 500MB
        ];
    }

    /**
     * Validações específicas para Campanhas - Folhetos
     */
    public static function getCampaignFolhetosValidationRules()
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:512000', // 100MB
        ];
    }

    /**
     * Validações específicas para Campanhas - Posts
     */
    public static function getCampaignPostsValidationRules()
    {
        return [
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240', // 10MB
        ];
    }

    /**
     * Validações específicas para Campanhas - Vídeos
     */
    public static function getCampaignVideosValidationRules()
    {
        return [
            'videos' => 'nullable|array',
            'videos.*' => 'file|mimes:mp4,mov,ogg,avi|max:' . config('upload.max_video_size'), // 500MB
        ];
    }

    /**
     * Validações específicas para Miscellaneous - Spot
     */
    public static function getMiscellaneousSpotValidationRules()
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:mp3,wav,ogg,aac,m4a|max:512000', // 500MB
        ];
    }

    /**
     * Validações específicas para Miscellaneous - Tag, Adesivo, Roteiro
     */
    public static function getMiscellaneousDocumentValidationRules()
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:512000', // 100MB
        ];
    }

    /**
     * Mensagens de erro personalizadas
     */
    public function messages()
    {
        return [
            // Produtos
            'gallery_images.*.image' => 'Apenas arquivos de imagem são permitidos na galeria.',
            'gallery_images.*.mimes' => 'As imagens devem ser nos formatos: JPEG, JPG, PNG ou WEBP.',
            'gallery_videos.*.mimes' => 'Os vídeos devem ser nos formatos: MP4, MOV ou OGG.',
            'gallery_videos.*.max' => 'Cada vídeo pode ter no máximo 100 MB.',
            
            // Biblioteca
            'files.*.mimes' => 'Arquivos permitidos: imagens (JPEG, JPG, PNG, WEBP), PDF, DOC, DOCX, XLS, XLSX.',
            
            // News
            'image.image' => 'Apenas arquivos de imagem são permitidos.',
            'image.mimes' => 'A imagem deve ser nos formatos: JPEG, JPG, PNG ou WEBP.',
            
            // Treinamento
            'videos.*.mimes' => 'Os vídeos devem ser nos formatos: MP4, MOV, OGG ou AVI.',
            'videos.*.max' => 'Cada vídeo pode ter no máximo 100 MB.',
            'files.*.mimes' => 'Apenas arquivos PDF são permitidos.',
            
            // Campanhas
            'images.*.mimes' => 'As imagens devem ser nos formatos: JPEG, JPG, PNG ou WEBP.',
            'videos.*.mimes' => 'Os vídeos devem ser nos formatos: MP4, MOV, OGG ou AVI.',
            'videos_reels.*.mimes' => 'Os Reels devem ser nos formatos: MP4, AVI ou MOV.',
            'videos_reels.*.max' => 'Cada vídeo de Reels pode ter no máximo 100 MB.',
            'videos_campaigns.*.mimes' => 'Os vídeos de campanha devem ser nos formatos: MP4, AVI ou MOV.',
            'videos_campaigns.*.max' => 'Cada vídeo de campanha pode ter no máximo 100 MB.',
            
            // Miscellaneous - Spot (áudio)
            'files.*.mimes' => 'Arquivos de áudio permitidos: MP3, WAV, OGG, AAC, M4A.',
            
            // Banner de campanha
            'banner.image' => 'O banner deve ser uma imagem válida.',
            'banner.mimes' => 'O banner deve ser nos formatos: JPEG, JPG, PNG ou WEBP.',
            'banner.max' => 'O banner não pode ser maior que 20MB.',
        ];
    }
}