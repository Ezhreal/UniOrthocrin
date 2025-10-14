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
            'gallery_images.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240', // 10MB
            'gallery_videos' => 'nullable|array', 
            'gallery_videos.*' => 'file|mimes:mp4,mov,ogg|max:102400', // 100MB
        ];
    }

    /**
     * Validações específicas para Biblioteca
     */
    public static function getLibraryValidationRules()
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpeg,jpg,png,webp,pdf,doc,docx,xls,xlsx|max:102400', // 100MB
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
     * Validações específicas para Treinamento
     */
    public static function getTrainingValidationRules()
    {
        return [
            'videos' => 'nullable|array',
            'videos.*' => 'file|mimes:mp4,mov,ogg,avi|max:102400', // 100MB
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:102400', // 100MB
        ];
    }

    /**
     * Validações específicas para Campanhas - Folhetos
     */
    public static function getCampaignFolhetosValidationRules()
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:102400', // 100MB
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
            'videos.*' => 'file|mimes:mp4,mov,ogg,avi|max:102400', // 100MB
        ];
    }

    /**
     * Validações específicas para Miscellaneous - Spot
     */
    public static function getMiscellaneousSpotValidationRules()
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:mp3,wav,ogg,aac,m4a|max:102400', // 100MB
        ];
    }

    /**
     * Validações específicas para Miscellaneous - Tag, Adesivo, Roteiro
     */
    public static function getMiscellaneousDocumentValidationRules()
    {
        return [
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf|max:102400', // 100MB
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
            
            // Biblioteca
            'files.*.mimes' => 'Arquivos permitidos: imagens (JPEG, JPG, PNG, WEBP), PDF, DOC, DOCX, XLS, XLSX.',
            
            // News
            'image.image' => 'Apenas arquivos de imagem são permitidos.',
            'image.mimes' => 'A imagem deve ser nos formatos: JPEG, JPG, PNG ou WEBP.',
            
            // Treinamento
            'videos.*.mimes' => 'Os vídeos devem ser nos formatos: MP4, MOV, OGG ou AVI.',
            'files.*.mimes' => 'Apenas arquivos PDF são permitidos.',
            
            // Campanhas
            'images.*.mimes' => 'As imagens devem ser nos formatos: JPEG, JPG, PNG ou WEBP.',
            'videos.*.mimes' => 'Os vídeos devem ser nos formatos: MP4, MOV, OGG ou AVI.',
            
            // Miscellaneous - Spot (áudio)
            'files.*.mimes' => 'Arquivos de áudio permitidos: MP3, WAV, OGG, AAC, M4A.',
        ];
    }
}