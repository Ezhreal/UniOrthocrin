<?php

namespace Tests\Feature;

use App\Jobs\UploadToFtpJob;
use App\Models\ChunkUpload;
use App\Models\FtpSync;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChunkUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Desabilitar CSRF para simulação das requisições POST do Uppy
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        // Configurar o driver FTP fake para testes
        Storage::fake('ftp');
        Storage::fake('private');
    }

    /**
     * Testa o upload de um chunk individual.
     */
    public function test_can_upload_individual_chunk(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $chunkFile = UploadedFile::fake()->create('chunk.bin', 1024); // 1KB

        $response = $this->withHeaders([
            'X-Unique-Upload-Id' => $uuid,
            'X-Chunk-Index' => '0',
            'X-Total-Chunks' => '3',
            'X-File-Name' => 'video_teste.mp4',
            'X-Total-Size' => '3072',
        ])->postJson(route('admin.upload.chunk'), [
            'file' => $chunkFile,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'uploading',
            'chunk_index' => 0,
            'uploaded_chunks' => 1,
        ]);

        $this->assertDatabaseHas('chunk_uploads', [
            'uuid' => $uuid,
            'filename' => 'video_teste.mp4',
            'total_chunks' => 3,
            'uploaded_chunks' => 1,
            'status' => 'uploading',
        ]);

        // Verificar se o arquivo do chunk foi gravado no local correto
        $chunkPath = storage_path("app/chunks/{$uuid}/0");
        $this->assertFileExists($chunkPath);

        // Limpar arquivos temporários criados no teste
        if (file_exists(storage_path("app/chunks/{$uuid}"))) {
            \Illuminate\Support\Facades\File::deleteDirectory(storage_path("app/chunks/{$uuid}"));
        }
    }

    /**
     * Testa o upload completo e remontagem automática após o último chunk.
     */
    public function test_automatically_merges_on_last_chunk(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $chunk1 = UploadedFile::fake()->create('chunk1.bin', 10);
        $chunk2 = UploadedFile::fake()->create('chunk2.bin', 10);

        // Upload chunk 0
        $this->actingAs($user);
        $response1 = $this->withHeaders([
            'X-Unique-Upload-Id' => $uuid,
            'X-Chunk-Index' => '0',
            'X-Total-Chunks' => '2',
            'X-File-Name' => 'teste_merge.txt',
            'X-Total-Size' => '20',
        ])->postJson(route('admin.upload.chunk'), [
            'file' => $chunk1,
        ]);

        if ($response1->getStatusCode() !== 200) {
            dd([
                'first_request_status' => $response1->getStatusCode(),
                'redirect_to' => $response1->headers->get('Location'),
                'content' => $response1->getContent()
            ]);
        }

        // Upload chunk 1 (Último)
        $this->actingAs($user);
        $response = $this->withHeaders([
            'X-Unique-Upload-Id' => $uuid,
            'X-Chunk-Index' => '1',
            'X-Total-Chunks' => '2',
            'X-File-Name' => 'teste_merge.txt',
            'X-Total-Size' => '20',
        ])->postJson(route('admin.upload.chunk'), [
            'file' => $chunk2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'completed',
            'uuid' => $uuid,
            'filename' => 'teste_merge.txt',
        ]);

        $this->assertDatabaseHas('chunk_uploads', [
            'uuid' => $uuid,
            'status' => 'completed',
        ]);

        // Verificar se os chunks temporários foram apagados e o arquivo final foi criado
        $this->assertDirectoryDoesNotExist(storage_path("app/chunks/{$uuid}"));
        
        $mergedPath = $response->json('local_path');
        $this->assertFileExists($mergedPath);

        // Limpar arquivo de teste
        if (file_exists($mergedPath)) {
            unlink($mergedPath);
        }
    }

    /**
     * Testa o processamento do Job de envio para o FTP.
     */
    public function test_upload_to_ftp_job_streams_file_and_cleans_up(): void
    {
        // Criar um arquivo local falso
        $tempPath = storage_path('app/temp');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $localFile = $tempPath . '/test_ftp_upload.txt';
        file_put_contents($localFile, 'conteudo fake de teste FTP');

        $ftpSync = FtpSync::create([
            'syncable_type' => 'App\Models\Media',
            'syncable_id' => 1,
            'file_id' => 999, // file_id para deletar arquivo local pós upload
            'local_path' => $localFile,
            'remote_path' => 'Media/1/test_ftp_upload.txt',
            'status' => 'pending'
        ]);

        // Executar o job diretamente
        $job = new UploadToFtpJob($ftpSync->id);
        $job->handle();

        // Verificar se foi enviado para o disco FTP fake
        Storage::disk('ftp')->assertExists('Media/1/test_ftp_upload.txt');
        $this->assertEquals('conteudo fake de teste FTP', Storage::disk('ftp')->get('Media/1/test_ftp_upload.txt'));

        // Verificar se o status foi atualizado no banco
        $ftpSync->refresh();
        $this->assertEquals('synced', $ftpSync->status);
        $this->assertNotNull($ftpSync->synced_at);

        // Verificar se o arquivo local foi deletado
        $this->assertFileDoesNotExist($localFile);
    }

    /**
     * Testa se o middleware intercepta os UUIDs do Uppy e os injeta nos arquivos da request.
     */
    public function test_middleware_intercepts_uppy_uploads_and_injects_uploaded_file(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        // Criar categoria de produto para validação
        $category = \App\Models\ProductCategory::create([
            'name' => 'Categoria Teste',
            'description' => 'Descricao Categoria Teste',
        ]);

        // Criar arquivo temporário remontado (com conteúdo de imagem PNG de 1x1 pixel)
        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $tempPath = storage_path('app/temp');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $localFile = $tempPath . "/{$uuid}_imagem_galeria.png";
        file_put_contents($localFile, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='));

        $chunkUpload = ChunkUpload::create([
            'uuid' => $uuid,
            'filename' => 'imagem_galeria.png',
            'mime_type' => 'image/png',
            'total_size' => strlen('conteudo fake de imagem'),
            'total_chunks' => 1,
            'uploaded_chunks' => 1,
            'status' => 'completed',
            'local_path' => $localFile,
        ]);

        // Mock do Queue para interceptar o job do FTP disparado pelo hook do Model File
        Queue::fake();

        $response = $this->postJson(route('admin.products.store'), [
            'name' => 'Produto Teste Uppy',
            'product_category_id' => $category->id,
            'status' => 'active',
            'uppy_uploads' => [
                'gallery_images[]' => [$uuid]
            ]
        ]);

        $response->assertRedirect();
        
        // Verificar se o produto foi criado
        $this->assertDatabaseHas('products', [
            'name' => 'Produto Teste Uppy',
        ]);

        $product = \App\Models\Product::where('name', 'Produto Teste Uppy')->first();

        // Verificar se a imagem foi anexada ao produto
        $this->assertCount(1, $product->images);
        $fileRecord = $product->images->first();
        $this->assertEquals('imagem_galeria.png', $fileRecord->name);

        // Verificar se a sincronização com o FTP foi registrada e o job disparado
        $this->assertDatabaseHas('ftp_syncs', [
            'syncable_type' => get_class($fileRecord),
            'syncable_id' => $fileRecord->id,
            'status' => 'pending',
        ]);

        Queue::assertPushed(UploadToFtpJob::class);

        // Limpar arquivo de teste se sobrou
        if (file_exists($localFile)) {
            @unlink($localFile);
        }
    }

    /**
     * Testa se o upload de vídeo via Uppy é associado corretamente ao relacionamento videos do produto.
     */
    public function test_product_video_attaches_correctly_as_video(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        $category = \App\Models\ProductCategory::create([
            'name' => 'Categoria Teste Video',
            'description' => 'Descricao Categoria Teste Video',
        ]);

        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $tempPath = storage_path('app/temp');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $localFile = $tempPath . "/{$uuid}_video_teste.mp4";
        file_put_contents($localFile, "\x00\x00\x00\x18ftypmp42\x00\x00\x00\x00mp42isom");

        $chunkUpload = ChunkUpload::create([
            'uuid' => $uuid,
            'filename' => 'video_teste.mp4',
            'mime_type' => 'video/mp4',
            'total_size' => strlen('fake mp4 video binary contents'),
            'total_chunks' => 1,
            'uploaded_chunks' => 1,
            'status' => 'completed',
            'local_path' => $localFile,
        ]);

        Queue::fake();

        $response = $this->postJson(route('admin.products.store'), [
            'name' => 'Produto Teste Video',
            'product_category_id' => $category->id,
            'status' => 'active',
            'uppy_uploads' => [
                'gallery_videos[]' => [$uuid]
            ]
        ]);

        $response->assertRedirect();

        $product = \App\Models\Product::where('name', 'Produto Teste Video')->first();
        $this->assertNotNull($product);

        $this->assertCount(1, $product->videos);
        $fileRecord = $product->videos->first();
        $this->assertEquals('video_teste.mp4', $fileRecord->name);
        $this->assertEquals('video', $fileRecord->type);
        $this->assertEquals('video', $fileRecord->pivot->file_type);

        if (file_exists($localFile)) {
            @unlink($localFile);
        }
    }

    /**
     * Testa se a associação imediata com o modelo é registrada no primeiro chunk.
     */
    public function test_immediate_association_during_chunk_upload(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        // Criar categoria de produto para criar um produto válido
        $category = \App\Models\ProductCategory::create([
            'name' => 'Categoria Teste Associacao',
            'description' => 'Descricao Categoria Teste Associacao',
        ]);

        $product = \App\Models\Product::create([
            'name' => 'Produto Teste Associacao Imediata',
            'product_category_id' => $category->id,
            'status' => 'active',
        ]);

        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $chunkFile = UploadedFile::fake()->create('chunk.bin', 1024);

        $response = $this->withHeaders([
            'X-Unique-Upload-Id' => $uuid,
            'X-Chunk-Index' => '0',
            'X-Total-Chunks' => '2',
            'X-File-Name' => 'imagem_associacao.png',
            'X-Total-Size' => '2048',
            'X-Model-Type' => get_class($product),
            'X-Model-Id' => $product->id,
            'X-Property' => 'gallery_images[]',
        ])->postJson(route('admin.upload.chunk'), [
            'file' => $chunkFile,
        ]);

        $response->assertStatus(200);

        // Verificar se a tabela chunk_uploads foi registrada com o modelo
        $this->assertDatabaseHas('chunk_uploads', [
            'uuid' => $uuid,
            'model_type' => get_class($product),
            'model_id' => $product->id,
            'property' => 'gallery_images[]',
        ]);

        // Verificar se o arquivo foi criado com status pending
        $this->assertDatabaseHas('files', [
            'chunk_upload_uuid' => $uuid,
            'status' => 'pending',
            'name' => 'imagem_associacao.png',
        ]);

        // Verificar se o relacionamento no pivot foi estabelecido
        $fileRecord = \App\Models\File::where('chunk_upload_uuid', $uuid)->first();
        $this->assertNotNull($fileRecord);

        $this->assertDatabaseHas('product_files', [
            'product_id' => $product->id,
            'file_id' => $fileRecord->id,
            'file_type' => 'image',
        ]);

        // Limpar diretório temporário
        if (file_exists(storage_path("app/chunks/{$uuid}"))) {
            \Illuminate\Support\Facades\File::deleteDirectory(storage_path("app/chunks/{$uuid}"));
        }
    }

    /**
     * Cria um usuário administrador manualmente.
     */
    private function createAdminUser(): User
    {
        $userType = new UserType();
        $userType->id = 1;
        $userType->name = 'Administrador';
        $userType->slug = 'admin';
        $userType->description = 'Administrador do sistema';
        $userType->level = 10;
        $userType->save();

        return User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'user_type_id' => $userType->id,
            'status' => 'active',
        ]);
    }

    /**
     * Testa se o middleware e o controller de campanhas associam corretamente os uploads.
     */
    public function test_campaign_attaches_uploads_successfully(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        // Criar arquivo temporário remontado (com conteúdo de imagem PNG de 1x1 pixel)
        $uuid = \Illuminate\Support\Str::uuid()->toString();
        $tempPath = storage_path('app/temp');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $localFile = $tempPath . "/{$uuid}_post_feed.png";
        file_put_contents($localFile, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='));

        $chunkUpload = ChunkUpload::create([
            'uuid' => $uuid,
            'filename' => 'post_feed.png',
            'mime_type' => 'image/png',
            'total_size' => strlen('conteudo fake de imagem'),
            'total_chunks' => 1,
            'uploaded_chunks' => 1,
            'status' => 'completed',
            'local_path' => $localFile,
        ]);

        Queue::fake();

        $response = $this->postJson(route('admin.campaigns.store'), [
            'name' => 'Campanha Teste Uppy',
            'status' => 'active',
            'uppy_uploads' => [
                'posts_feed[]' => [$uuid]
            ]
        ]);

        $response->assertRedirect();

        // Verificar se a campanha foi criada
        $this->assertDatabaseHas('campaigns', [
            'name' => 'Campanha Teste Uppy',
        ]);

        $campaign = \App\Models\Campaign::where('name', 'Campanha Teste Uppy')->first();

        // Verificar se o post foi criado e associado
        $this->assertCount(1, $campaign->posts);
        $post = $campaign->posts->first();
        $this->assertEquals('feeds', $post->type);

        // Verificar se o arquivo foi anexado ao post
        $this->assertCount(1, $post->files);
        $fileRecord = $post->files->first();
        $this->assertEquals('post_feed.png', $fileRecord->name);

        // Limpar arquivo de teste se sobrou
        if (file_exists($localFile)) {
            @unlink($localFile);
        }
    }
}
