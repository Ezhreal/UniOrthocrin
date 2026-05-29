<?php

namespace App\Traits;

use App\Models\ChunkUpload;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait HandlesChunkUploads
{
    /**
     * Associa os uploads fracionados do Uppy (Chunk Uploads) ao modelo.
     */
    protected function associateChunkUploads(Request $request, $model)
    {
        if ($request->has('uppy_uploads')) {
            $uppyUploads = $request->input('uppy_uploads');
            if (is_array($uppyUploads)) {
                foreach ($uppyUploads as $inputName => $uuids) {
                    if (is_array($uuids)) {
                        foreach ($uuids as $uuid) {
                            $chunkUpload = ChunkUpload::where('uuid', $uuid)->first();
                            if ($chunkUpload) {
                                $property = str_ends_with($inputName, '[]') ? substr($inputName, 0, -2) : $inputName;
                                
                                Log::info('[HandlesChunkUploads] Associating chunk upload to model', [
                                    'uuid' => $uuid,
                                    'model_type' => get_class($model),
                                    'model_id' => $model->id,
                                    'property' => $property,
                                    'status' => $chunkUpload->status
                                ]);

                                // Associa a referência de model no banco para rastrear o destino pós upload
                                $chunkUpload->update([
                                    'model_type' => get_class($model),
                                    'model_id' => $model->id,
                                    'property' => $property
                                ]);

                                // Se o upload já foi finalizado, vincula fisicamente
                                if ($chunkUpload->status === 'completed') {
                                    $chunkUpload->processAttachment();
                                } elseif ($chunkUpload->status !== 'injected') {
                                    // Se o upload está ativo/pendente (e não injetado via middleware), cria o registro do arquivo como pending e o vincula ao modelo
                                    $this->createPendingFileAttachment($chunkUpload, $model, $property);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Cria um registro de File com status pending e o associa ao modelo destino para exibição.
     */
    protected function createPendingFileAttachment(ChunkUpload $chunkUpload, $model, string $property)
    {
        // Determina o tipo simplificado de arquivo com base no mime_type original
        $fileType = 'pdf';
        if ($chunkUpload->mime_type) {
            if (str_starts_with($chunkUpload->mime_type, 'image/')) {
                $fileType = 'image';
            } elseif (str_starts_with($chunkUpload->mime_type, 'video/')) {
                $fileType = 'video';
            } elseif (str_starts_with($chunkUpload->mime_type, 'audio/')) {
                $fileType = 'audio';
            } elseif ($chunkUpload->mime_type === 'application/pdf') {
                $fileType = 'pdf';
            }
        }

        $extension = strtolower(pathinfo($chunkUpload->filename, PATHINFO_EXTENSION));

        // Cria ou recupera o registro de arquivo com status pending para evitar duplicações
        $fileRecord = File::firstOrCreate(
            ['chunk_upload_uuid' => $chunkUpload->uuid],
            [
                'name' => $chunkUpload->filename,
                'path' => 'placeholder_' . $chunkUpload->uuid, // caminho marcador temporário
                'type' => $fileType,
                'extension' => $extension,
                'mime_type' => $chunkUpload->mime_type,
                'size' => $chunkUpload->total_size,
                'status' => 'pending',
                'order' => 0,
            ]
        );

        $baseClass = class_basename($model);
        
        // Ajusta as propriedades específicas de cada classe de modelo do sistema
        if ($baseClass === 'Product') {
            if ($property === 'gallery_images') {
                $property = 'images';
            } elseif ($property === 'gallery_videos') {
                $property = 'videos';
            }
        }

        // Executa a vinculação do relacionamento no banco de dados
        if ($baseClass === 'News' && ($property === 'image' || $property === 'mainFile' || $property === 'news_file_id')) {
            $model->news_file_id = $fileRecord->id;
            $model->save();
        } elseif ($baseClass === 'Campaign') {
            // Mapeia e cria posts, pastas, vídeos ou coleções de miscelânea da campanha
            if (str_starts_with($property, 'posts_')) {
                $postTypes = [
                    'posts_feed' => 'feeds',
                    'posts_stories_mg_sp' => 'stories_mg_sp',
                    'posts_stories_df_es' => 'stories_df_es'
                ];
                $type = $postTypes[$property] ?? 'feeds';
                $post = $model->posts()->where('name', $chunkUpload->filename)->first();
                if (!$post) {
                    $post = $model->posts()->create([
                        'name' => $chunkUpload->filename,
                        'type' => $type,
                        'status' => 'active'
                    ]);
                }
                if (!$post->files()->where('file_id', $fileRecord->id)->exists()) {
                    $post->files()->attach($fileRecord->id, [
                        'file_type' => 'image',
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);
                }
            } elseif (str_starts_with($property, 'folder_')) {
                $folderTypes = [
                    'folder_mg_sp' => 'MG/SP',
                    'folder_df_es' => 'DF/ES'
                ];
                $state = $folderTypes[$property] ?? 'MG/SP';
                $folder = $model->folders()->where('name', $chunkUpload->filename)->first();
                if (!$folder) {
                    $folder = $model->folders()->create([
                        'name' => $chunkUpload->filename,
                        'state' => $state,
                        'status' => 'active'
                    ]);
                }
                if (!$folder->files()->where('file_id', $fileRecord->id)->exists()) {
                    $folder->files()->attach($fileRecord->id, [
                        'file_type' => $fileType,
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);
                }
            } elseif (str_starts_with($property, 'videos_')) {
                $videoTypes = [
                    'videos_reels' => 'reels',
                    'videos_campaigns' => 'marketing_campaigns'
                ];
                $type = $videoTypes[$property] ?? 'reels';
                $video = $model->videos()->where('name', $chunkUpload->filename)->first();
                if (!$video) {
                    $video = $model->videos()->create([
                        'name' => $chunkUpload->filename,
                        'type' => $type,
                        'status' => 'active'
                    ]);
                }
                if (!$video->files()->where('file_id', $fileRecord->id)->exists()) {
                    $video->files()->attach($fileRecord->id, [
                        'file_type' => 'video',
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);
                }
            } elseif (str_starts_with($property, 'misc_')) {
                $type = substr($property, 5);
                $misc = $model->miscellaneous()->where('name', $chunkUpload->filename)->first();
                if (!$misc) {
                    $misc = $model->miscellaneous()->create([
                        'name' => $chunkUpload->filename,
                        'type' => $type,
                        'status' => 'active'
                    ]);
                }
                if (!$misc->files()->where('file_id', $fileRecord->id)->exists()) {
                    $misc->files()->attach($fileRecord->id, [
                        'file_type' => $fileType,
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);
                }
            }
        } elseif (method_exists($model, $property)) {
            $relation = $model->{$property}();

            if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                // Usa files() sem filtros de pivot para verificar existência e fazer attach corretamente.
                // Usar a relação filtrada (ex: videos() com wherePivot) causaria query na coluna errada.
                $filesRelation = method_exists($model, 'files') ? $model->files() : $relation;
                if (!$filesRelation->where('file_id', $fileRecord->id)->exists()) {
                    $order = $filesRelation->count() + 1;
                    $filesRelation->attach($fileRecord->id, [
                        'file_type' => $fileType,
                        'sort_order' => $order,
                        'is_primary' => $order === 1,
                    ]);
                }
            } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                if (!$relation->where('id', $fileRecord->id)->exists()) {
                    $relation->save($fileRecord);
                }
            }
        } elseif (method_exists($model, 'files')) {
            if (!$model->files()->where('file_id', $fileRecord->id)->exists()) {
                $order = $model->files()->count() + 1;
                $model->files()->attach($fileRecord->id, [
                    'file_type' => $fileType,
                    'sort_order' => $order,
                    'is_primary' => $order === 1
                ]);
            }
        }
    }
}
