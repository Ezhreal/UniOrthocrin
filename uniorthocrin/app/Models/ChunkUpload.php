<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChunkUpload extends Model
{
    use HasFactory;

    protected $table = 'chunk_uploads';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'filename',
        'mime_type',
        'total_size',
        'total_chunks',
        'uploaded_chunks',
        'status',
        'local_path',
        'model_type',
        'model_id',
        'property',
        'error_message'
    ];

    // Scopes
    public function scopeUploading($query)
    {
        return $query->where('status', 'uploading');
    }

    public function scopeMerging($query)
    {
        return $query->where('status', 'merging');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Processa a vinculação do arquivo remontado ao modelo associado.
     */
    public function processAttachment(): bool
    {
        if ($this->status !== 'completed' || !$this->model_type || !$this->model_id || !$this->property) {
            \Illuminate\Support\Facades\Log::info('[ChunkUpload] Condições insuficientes para processar anexo', [
                'uuid' => $this->uuid,
                'status' => $this->status,
                'model_type' => $this->model_type,
                'model_id' => $this->model_id,
                'property' => $this->property
            ]);
            return false;
        }

        if (!file_exists($this->local_path)) {
            \Illuminate\Support\Facades\Log::error('[ChunkUpload] Arquivo local não encontrado para processar anexo', [
                'uuid' => $this->uuid,
                'local_path' => $this->local_path
            ]);
            return false;
        }

        try {
            $modelClass = $this->model_type;
            $model = $modelClass::find($this->model_id);
            if (!$model) {
                \Illuminate\Support\Facades\Log::error('[ChunkUpload] Modelo associado não encontrado', [
                    'model_type' => $this->model_type,
                    'model_id' => $this->model_id
                ]);
                return false;
            }

            // Determinar o tipo do arquivo
            $fileType = 'pdf'; // Default
            if ($this->mime_type) {
                if (str_starts_with($this->mime_type, 'image/')) {
                    $fileType = 'image';
                } elseif (str_starts_with($this->mime_type, 'video/')) {
                    $fileType = 'video';
                } elseif (str_starts_with($this->mime_type, 'audio/')) {
                    $fileType = 'audio';
                } elseif ($this->mime_type === 'application/pdf') {
                    $fileType = 'pdf';
                }
            }

            // Mapeia o diretório de destino com base no tipo do model
            $targetDir = '';
            $baseClass = class_basename($modelClass);
            
            switch ($baseClass) {
                case 'Product':
                    if ($this->property === 'gallery_images') {
                        $targetDir = "private/products/{$model->id}/images";
                        $fileType = 'image';
                    } elseif ($this->property === 'gallery_videos') {
                        $targetDir = "private/products/{$model->id}/videos";
                        $fileType = 'video';
                    } else {
                        $targetDir = "private/products/{$model->id}";
                    }
                    break;
                case 'Campaign':
                    if (str_starts_with($this->property, 'posts_')) {
                        $targetDir = "private/campaigns/{$model->id}/posts";
                        $fileType = 'image';
                    } elseif (str_starts_with($this->property, 'folder_')) {
                        $targetDir = "private/campaigns/{$model->id}/folders";
                    } elseif (str_starts_with($this->property, 'videos_')) {
                        $targetDir = "private/campaigns/{$model->id}/videos";
                        $fileType = 'video';
                    } elseif (str_starts_with($this->property, 'misc_')) {
                        $targetDir = "private/campaigns/{$model->id}/miscellaneous";
                    } else {
                        $targetDir = "private/campaigns/{$model->id}";
                    }
                    break;
                case 'Media':
                    $targetDir = "private/media/{$model->id}";
                    break;
                case 'Library':
                    $targetDir = "private/library/{$model->id}";
                    break;
                case 'Training':
                    $targetDir = "private/trainings/{$model->id}";
                    break;
                case 'News':
                    $targetDir = "private/news/{$model->id}";
                    $fileType = 'image';
                    break;
                case 'CampaignPost':
                    $targetDir = "private/campaigns/{$model->campaign_id}/posts";
                    $fileType = 'image';
                    break;
                case 'CampaignFolder':
                    $targetDir = "private/campaigns/{$model->campaign_id}/folders";
                    break;
                case 'CampaignVideo':
                    $targetDir = "private/campaigns/{$model->campaign_id}/videos";
                    $fileType = 'video';
                    break;
                case 'CampaignMiscellaneous':
                    $targetDir = "private/campaigns/{$model->campaign_id}/miscellaneous";
                    break;
                default:
                    $pluralName = strtolower(\Illuminate\Support\Str::plural($baseClass));
                    $targetDir = "private/{$pluralName}/{$model->id}";
                    break;
            }

            // Gerar nome único para o storage privado
            $extension = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
            $safeName = \Illuminate\Support\Str::random(40) . '.' . $extension;
            $targetPath = $targetDir . '/' . $safeName;

            \Illuminate\Support\Facades\Log::info('[ChunkUpload] Movendo arquivo para o destino final', [
                'uuid' => $this->uuid,
                'target_path' => $targetPath
            ]);

            // Certifica de que a pasta de destino exista
            \Illuminate\Support\Facades\Storage::disk('private')->makeDirectory($targetDir);

            // Mover via stream
            $stream = fopen($this->local_path, 'r');
            if ($stream) {
                \Illuminate\Support\Facades\Storage::disk('private')->writeStream($targetPath, $stream);
                fclose($stream);
            } else {
                throw new \RuntimeException("Não foi possível abrir o arquivo temporário local para stream: {$this->local_path}");
            }

            // Deleta o arquivo temporário
            if (file_exists($this->local_path)) {
                @unlink($this->local_path);
            }

            // Verifica se o registro de arquivo com status pending já foi criado anteriormente
            $fileRecord = File::where('chunk_upload_uuid', $this->uuid)->first();

            // Prepara a property normalizada para uso no attach (remove sufixo [] e mapeia aliases)
            $property = $this->property;
            if ($baseClass === 'Product') {
                if ($property === 'gallery_images') {
                    $property = 'images';
                } elseif ($property === 'gallery_videos') {
                    $property = 'videos';
                }
            }

            if ($fileRecord) {
                // Atualiza o registro pendente preenchendo o caminho final e marcando como pronto
                $fileRecord->update([
                    'path'      => $targetPath,
                    'status'    => 'ready',
                    'size'      => $this->total_size,
                    'mime_type' => $this->mime_type,
                ]);

                \Illuminate\Support\Facades\Log::info('[ChunkUpload] Registro File existente atualizado para ready', [
                    'id'   => $fileRecord->id,
                    'path' => $targetPath,
                ]);

                // O attach ao modelo pode não ter sido feito quando o File estava como pending.
                // Garante o vínculo agora que o arquivo está pronto.
                $this->attachFileToModel($model, $baseClass, $property, $fileRecord, $fileType);

            } else {
                // Cria o registro do arquivo pronto caso o formulário não tenha sido salvo antes do upload terminar
                $fileRecord = File::create([
                    'name'              => $this->filename,
                    'path'              => $targetPath,
                    'type'              => $fileType,
                    'extension'         => $extension,
                    'mime_type'         => $this->mime_type,
                    'size'              => $this->total_size,
                    'order'             => 0,
                    'status'            => 'ready',
                    'chunk_upload_uuid' => $this->uuid,
                ]);

                \Illuminate\Support\Facades\Log::info('[ChunkUpload] Novo registro File criado com status ready', [
                    'id'   => $fileRecord->id,
                    'path' => $targetPath,
                ]);

                $this->attachFileToModel($model, $baseClass, $property, $fileRecord, $fileType);
            }

            // Dispara otimização assíncrona caso seja imagem
            if ($fileRecord && $fileRecord->type === 'image') {
                \App\Jobs\OptimizeImage::dispatch($fileRecord);
            }

            // Agenda a sincronização para o FTP de produção
            if ($fileRecord && str_starts_with($fileRecord->path, 'private/')) {
                $exists = \App\Models\FtpSync::where('file_id', $fileRecord->id)->exists();
                if (!$exists) {
                    $isThumbnail = str_contains($fileRecord->path, '/thumb/')
                        || str_contains($fileRecord->path, '/thumbnail')
                        || str_contains($fileRecord->path, '_thumb');

                    $sync = \App\Models\FtpSync::create([
                        'syncable_type' => get_class($fileRecord),
                        'syncable_id'   => $fileRecord->id,
                        'file_id'       => $isThumbnail ? null : $fileRecord->id,
                        'local_path'    => storage_path('app/' . $fileRecord->path),
                        'remote_path'   => $fileRecord->path,
                        'status'        => 'pending',
                    ]);

                    \App\Jobs\UploadToFtpJob::dispatch($sync->id);
                }
            }

            \Illuminate\Support\Facades\Log::info('[ChunkUpload] Arquivo vinculado com sucesso ao modelo', [
                'uuid'  => $this->uuid,
                'model' => $modelClass,
                'id'    => $model->id,
            ]);

            // Exclui o registro de controle do chunk
            $this->delete();

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[ChunkUpload] Falha ao processar anexo do chunk', [
                'uuid'  => $this->uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Executa o attach do fileRecord ao modelo, usando files() quando a relação nomeada
     * possui wherePivot que interfere na query de existência/count.
     */
    private function attachFileToModel($model, string $baseClass, string $property, File $fileRecord, string $fileType): void
    {
        if ($baseClass === 'News' && ($property === 'image' || $property === 'mainFile' || $property === 'news_file_id')) {
            $model->news_file_id = $fileRecord->id;
            $model->save();
        } elseif ($baseClass === 'Campaign') {
            if (str_starts_with($property, 'posts_')) {
                $postTypes = ['posts_feed' => 'feeds', 'posts_stories_mg_sp' => 'stories_mg_sp', 'posts_stories_df_es' => 'stories_df_es'];
                $type = $postTypes[$property] ?? 'feeds';
                $post = $model->posts()->where('name', $this->filename)->first()
                    ?? $model->posts()->create(['name' => $this->filename, 'type' => $type, 'status' => 'active']);
                if (!$post->files()->where('file_id', $fileRecord->id)->exists()) {
                    $post->files()->attach($fileRecord->id, ['file_type' => 'image', 'sort_order' => 0, 'is_primary' => true]);
                }
            } elseif (str_starts_with($property, 'folder_')) {
                $folderTypes = ['folder_mg_sp' => 'MG/SP', 'folder_df_es' => 'DF/ES'];
                $state = $folderTypes[$property] ?? 'MG/SP';
                $folder = $model->folders()->where('name', $this->filename)->first()
                    ?? $model->folders()->create(['name' => $this->filename, 'state' => $state, 'status' => 'active']);
                if (!$folder->files()->where('file_id', $fileRecord->id)->exists()) {
                    $folder->files()->attach($fileRecord->id, ['file_type' => $fileType, 'sort_order' => 0, 'is_primary' => true]);
                }
            } elseif (str_starts_with($property, 'videos_')) {
                $videoTypes = ['videos_reels' => 'reels', 'videos_campaigns' => 'marketing_campaigns'];
                $type = $videoTypes[$property] ?? 'reels';
                $video = $model->videos()->where('name', $this->filename)->first()
                    ?? $model->videos()->create(['name' => $this->filename, 'type' => $type, 'status' => 'active']);
                if (!$video->files()->where('file_id', $fileRecord->id)->exists()) {
                    $video->files()->attach($fileRecord->id, ['file_type' => 'video', 'sort_order' => 0, 'is_primary' => true]);
                }
            } elseif (str_starts_with($property, 'misc_')) {
                $type = substr($property, 5);
                $misc = $model->miscellaneous()->where('name', $this->filename)->first()
                    ?? $model->miscellaneous()->create(['name' => $this->filename, 'type' => $type, 'status' => 'active']);
                if (!$misc->files()->where('file_id', $fileRecord->id)->exists()) {
                    $misc->files()->attach($fileRecord->id, ['file_type' => $fileType, 'sort_order' => 0, 'is_primary' => true]);
                }
            }
        } elseif (method_exists($model, $property)) {
            // Usa files() sem filtros de pivot para evitar query incorreta no belong-to-many filtrado
            $filesRelation = method_exists($model, 'files') ? $model->files() : $model->{$property}();
            if (!$filesRelation->where('file_id', $fileRecord->id)->exists()) {
                $order = $filesRelation->count() + 1;
                $filesRelation->attach($fileRecord->id, [
                    'file_type'  => $fileType,
                    'sort_order' => $order,
                    'is_primary' => $order === 1,
                ]);
                \Illuminate\Support\Facades\Log::info('[ChunkUpload] File vinculado ao modelo via files()', [
                    'file_id'    => $fileRecord->id,
                    'model'      => $baseClass,
                    'property'   => $property,
                    'file_type'  => $fileType,
                ]);
            }
        } elseif (method_exists($model, 'files')) {
            $filesRelation = $model->files();
            if (!$filesRelation->where('file_id', $fileRecord->id)->exists()) {
                $order = $filesRelation->count() + 1;
                $filesRelation->attach($fileRecord->id, [
                    'file_type'  => $fileType,
                    'sort_order' => $order,
                    'is_primary' => $order === 1,
                ]);
            }
        }
    }
}
