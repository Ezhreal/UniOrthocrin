<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileValidationRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSeries;
use App\Models\File;
use App\Models\UserType;
use App\Models\ProductPermission;
use App\Models\OneDriveSync;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'series', 'images', 'videos'])
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();
        $series = ProductSeries::with('category')->orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'series', 'userTypes'));
    }

    public function store(Request $request)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_series_id' => 'nullable|exists:product_series,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getProductValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $publishOneDrive = $request->boolean('publish_onedrive');
        DB::beginTransaction();
        try {
            // Criar produto
            $product = Product::create($request->only([
                'name', 'description', 'product_category_id', 'product_series_id', 'status'
            ]));

            // Thumbnail opcional
            if ($request->hasFile('thumbnail')) {
                $thumb = $request->file('thumbnail');
                $thumbPath = $thumb->store("private/products/{$product->id}/thumb", 'private');
                $product->thumbnail_path = $thumbPath;
                $product->save();
            }

            // Upload de imagens
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store("private/products/{$product->id}/images", 'private');
                    
                    $file = File::create([
                        'name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'type' => 'image',
                        'extension' => $this->getFileExtension($image->getClientOriginalName()),
                        'order' => $index + 1,
                    ]);

                    $product->images()->attach($file->id, [
                        'file_type' => 'image',
                        'sort_order' => $index + 1,
                        'is_primary' => $index === 0,
                    ]);

                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Products/' . $product->id . '/images/' . $file->id . '-image.' . $this->getFileExtension($image->getClientOriginalName());
                        $sync = $this->createOneDriveSync($product, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }

            // Upload de vídeos
            if ($request->hasFile('gallery_videos')) {
                foreach ($request->file('gallery_videos') as $index => $video) {
                    $path = $video->store("private/products/{$product->id}/videos", 'private');
                    
                    $file = File::create([
                        'name' => $video->getClientOriginalName(),
                        'path' => $path,
                        'size' => $video->getSize(),
                        'mime_type' => $video->getMimeType(),
                        'type' => 'video',
                        'extension' => $this->getFileExtension($video->getClientOriginalName()),
                        'order' => $index + 1,
                    ]);

                    $product->videos()->attach($file->id, [
                        'file_type' => 'video',
                        'sort_order' => $index + 1,
                        'is_primary' => $index === 0,
                    ]);

                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Products/' . $product->id . '/videos/' . $file->id . '-video.' . $this->getFileExtension($video->getClientOriginalName());
                        $sync = $this->createOneDriveSync($product, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }

            // Criar permissões
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permission) {
                    ProductPermission::create([
                        'product_id' => $product->id,
                        'user_type_id' => $permission['user_type_id'],
                        'can_view' => isset($permission['can_view']) ? (bool)$permission['can_view'] : false,
                        'can_download' => isset($permission['can_download']) ? (bool)$permission['can_download'] : false,
                    ]);
                }
            }

            // Administrador sempre tem permissão total
            ProductPermission::updateOrCreate([
                'product_id' => $product->id,
                'user_type_id' => 1, // ID do Administrador
            ], [
                'can_view' => true,
                'can_download' => true,
            ]);

            DB::commit();

            // Criar notificação automática para usuários com permissão
            NotificationService::notifyNewProduct($product->id, $product->name);

            return redirect()->route('admin.products.index')
                ->with('success', 'Produto criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'series', 'images', 'videos', 'permissions.userType']);
        $userTypes = UserType::orderBy('name')->get();
        
        return view('admin.products.show', compact('product', 'userTypes'));
    }

    public function edit(Product $product)
    {
        $product->load(['category', 'series', 'images', 'videos', 'permissions.userType']);
        $categories = ProductCategory::orderBy('name')->get();
        $series = ProductSeries::with('category')->orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'series', 'userTypes'));
    }

    public function update(Request $request, Product $product)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_series_id' => 'nullable|exists:product_series,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getProductValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $publishOneDrive = $request->boolean('publish_onedrive');
        DB::beginTransaction();
        try {
            // Atualizar produto
            $product->update($request->only([
                'name', 'description', 'product_category_id', 'product_series_id', 'status'
            ]));

            if ($request->hasFile('thumbnail')) {
                $thumb = $request->file('thumbnail');
                $thumbPath = $thumb->store("private/products/{$product->id}/thumb", 'private');
                $product->thumbnail_path = $thumbPath;
                $product->save();
                if ($publishOneDrive && $thumbPath) {
                    $localPath = storage_path('app/' . $thumbPath);
                    $remotePath = 'Products/' . $product->id . '/thumb-' . $product->id . '.' . $this->getFileExtension($thumb->getClientOriginalName());
                    $sync = $this->createOneDriveSync($product, $path, $remotePath);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                }
            }

            // Upload de novas imagens
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store("private/products/{$product->id}/images", 'private');
                    
                    $file = File::create([
                        'name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'type' => 'image',
                        'extension' => $this->getFileExtension($image->getClientOriginalName()),
                        'order' => $index + 1,
                    ]);

                    $product->images()->attach($file->id, [
                        'file_type' => 'image',
                        'sort_order' => $product->images()->count() + $index + 1,
                        'is_primary' => false,
                    ]);
                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Products/' . $product->id . '/images/' . $file->id . '-image.' . $this->getFileExtension($image->getClientOriginalName());
                        $sync = $this->createOneDriveSync($product, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }

            // Upload de novos vídeos
            if ($request->hasFile('gallery_videos')) {
                foreach ($request->file('gallery_videos') as $index => $video) {
                    $path = $video->store("private/products/{$product->id}/videos", 'private');
                    
                    $file = File::create([
                        'name' => $video->getClientOriginalName(),
                        'path' => $path,
                        'size' => $video->getSize(),
                        'mime_type' => $video->getMimeType(),
                        'type' => 'video',
                        'extension' => $this->getFileExtension($video->getClientOriginalName()),
                        'order' => $index + 1,
                    ]);

                    $product->videos()->attach($file->id, [
                        'file_type' => 'video',
                        'sort_order' => $product->videos()->count() + $index + 1,
                        'is_primary' => false,
                    ]);
                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Products/' . $product->id . '/videos/' . $file->id . '-video.' . $this->getFileExtension($video->getClientOriginalName());
                        $sync = $this->createOneDriveSync($product, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }

            // Atualizar permissões
            if ($request->has('permissions')) {
                // Remover permissões existentes (exceto admin)
                $product->permissions()->where('user_type_id', '!=', 1)->delete();
                
                // Criar novas permissões
                foreach ($request->permissions as $permission) {
                    try {
                        ProductPermission::create([
                            'product_id' => $product->id,
                            'user_type_id' => $permission['user_type_id'],
                            'can_view' => isset($permission['can_view']) ? (bool)$permission['can_view'] : false,
                            'can_download' => isset($permission['can_download']) ? (bool)$permission['can_download'] : false,
                        ]);
                    } catch (\Exception $e) {
                        // Ignorar erro de duplicata (admin já existe)
                        if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                            throw $e;
                        }
                    }
                }
            }

            // Administrador sempre tem permissão total
            ProductPermission::updateOrCreate([
                'product_id' => $product->id,
                'user_type_id' => 1, // ID do Administrador
            ], [
                'can_view' => true,
                'can_download' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            // Deletar arquivos físicos
            if ($product->images) {
                foreach ($product->images as $image) {
                    if ($image->path && Storage::disk('private')->exists($image->path)) {
                        Storage::disk('private')->delete($image->path);
                    }
                }
            }
            
            if ($product->videos) {
                foreach ($product->videos as $video) {
                    if ($video->path && Storage::disk('private')->exists($video->path)) {
                        Storage::disk('private')->delete($video->path);
                    }
                }
            }

            // Deletar produto (cascade vai deletar as relações)
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produto deletado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao deletar produto: ' . $e->getMessage());
        }
    }

    public function deleteFile(Product $product, File $file)
    {
        try {
            // Verificar se o arquivo pertence ao produto
            if (!$product->images->contains($file) && !$product->videos->contains($file)) {
                return response()->json(['error' => 'Arquivo não encontrado'], 404);
            }

            // Deletar arquivo físico
            if ($file->path && Storage::disk('private')->exists($file->path)) {
                Storage::disk('private')->delete($file->path);
            }

            // Remover relação
            $product->images()->detach($file->id);
            $product->videos()->detach($file->id);

            // Deletar registro do arquivo
            $file->delete();

            return response()->json(['success' => 'Arquivo deletado com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar arquivo: ' . $e->getMessage()], 500);
        }
    }
    
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Helper para criar sincronização OneDrive
     */
    private function createOneDriveSync($product, $filePath, $remotePath)
    {
        $sync = OneDriveSync::create([
            'syncable_type' => get_class($product),
            'syncable_id' => $product->id,
            'file_path' => $filePath,
            'remote_path' => $remotePath,
            'status' => 'pending'
        ]);

        return $sync;
    }

    public function syncToOneDrive(Product $product)
    {
        try {
            $syncedCount = 0;
            
            // Sincronizar arquivos que ainda não foram enviados
            foreach ($product->files as $file) {
                // Verificar se já existe sync para este arquivo
                $existingSync = OneDriveSync::where('syncable_type', get_class($product))
                    ->where('syncable_id', $product->id)
                    ->where('file_path', $file->path)
                    ->first();
                
                if (!$existingSync) {
                    // Criar novo sync
                    $localPath = storage_path('app/' . $file->path);
                    $remotePath = 'Products/' . $product->id . '/' . $file->type . 's/' . $file->name;
                    
                    $sync = $this->createOneDriveSync($product, $file->path, $remotePath);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    $syncedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sincronização iniciada para {$syncedCount} arquivo(s).",
                'synced_count' => $syncedCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao iniciar sincronização: ' . $e->getMessage()
            ], 500);
        }
    }

    public function retryOneDriveSync(Product $product)
    {
        try {
            $retryCount = 0;
            
            // Reenviar arquivos que falharam
            $failedSyncs = OneDriveSync::where('syncable_type', get_class($product))
                ->where('syncable_id', $product->id)
                ->where('status', 'failed')
                ->get();
            
            foreach ($failedSyncs as $sync) {
                $localPath = storage_path('app/' . $sync->file_path);
                
                if (file_exists($localPath)) {
                    $sync->update(['status' => 'pending']);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $sync->remote_path, $sync->id);
                    $retryCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Tentativa de reenvio iniciada para {$retryCount} arquivo(s).",
                'retry_count' => $retryCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao tentar reenviar: ' . $e->getMessage()
            ], 500);
        }
    }
}
