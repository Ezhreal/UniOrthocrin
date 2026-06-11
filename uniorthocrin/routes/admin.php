<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductSeriesController;
use App\Http\Controllers\Admin\TrainingCategoryController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\LibraryCategoryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MediaCategoryController;
use App\Http\Controllers\Admin\HelpController;
use App\Http\Controllers\Admin\ChunkUploadController;

// Auth e Redirects
Route::get('admin/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
Route::post('admin/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('admin.logout');

Route::get('admin', function () {
    if (Auth::check() && Auth::user()->isAdmin()) return redirect()->route('admin.dashboard');
    if (Auth::check()) {
        $profile = app(\App\Services\ProfileService::class)->getActiveProfile();
        return redirect()->route('profile.index', ['profile_slug' => $profile->slug ?? 'home']);
    }
    return redirect()->route('admin.login');
})->name('admin.redirect');

Route::post('profile/switch/{id}', function ($id, \App\Services\ProfileService $profileService) {
    if ($profileService->switchProfile($id)) {
        return redirect()->route('profile.index', ['profile_slug' => session('active_profile_slug')])->with('success', 'Perfil alterado!');
    }
    return back()->with('error', 'Erro ao trocar perfil.');
})->name('profile.switch')->middleware('auth');

// Grupo Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminOnly::class, \App\Http\Middleware\NoStoreForAuthenticated::class])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Chunk Upload
    Route::post('upload/chunk', [ChunkUploadController::class, 'uploadChunk'])->name('upload.chunk');
    Route::get('upload-manager', [ChunkUploadController::class, 'showUploadManager'])->name('upload.manager');
    Route::get('upload/status/{uuid}', [ChunkUploadController::class, 'getUploadStatus'])->name('upload.status');
    Route::post('upload/sync/{uuid}', [ChunkUploadController::class, 'sync'])->name('upload.sync');
    Route::post('upload/sync-all', [ChunkUploadController::class, 'syncAll'])->name('upload.sync-all');
    Route::post('upload/clear-all', [ChunkUploadController::class, 'clearAll'])->name('upload.clear-all');
    
    // 1. Produtos
    Route::resource('produtos', ProductController::class)->names('products')->parameters(['produtos' => 'product']);
    Route::get('produto/{product}', [ProductController::class, 'show']);
    Route::post('produtos/{product}/files', [ProductController::class, 'uploadFiles'])->name('products.files.upload');
    Route::delete('produtos/{product}/files/{file}', [ProductController::class, 'deleteFile'])->name('products.files.delete');
    Route::post('produtos/{product}/permissions', [ProductController::class, 'updatePermissions'])->name('products.permissions.update');
    Route::post('produtos/{product}/onedrive/sync', [ProductController::class, 'syncToOneDrive'])->name('products.onedrive.sync');
    Route::post('produtos/{product}/onedrive/retry', [ProductController::class, 'retryOneDriveSync'])->name('products.onedrive.retry');
    Route::resource('produtos-categorias', ProductCategoryController::class)->names('product-categories')->parameters(['produtos-categorias' => 'product_category'])->only(['index', 'store', 'destroy']);
    Route::resource('produtos-series', ProductSeriesController::class)->names('product-series')->parameters(['produtos-series' => 'product_series'])->only(['index', 'store', 'destroy']);
    
    // 2. Usuarios
    Route::resource('usuarios', UserController::class)->names('users')->parameters(['usuarios' => 'user']);
    Route::get('usuario/{user}', [UserController::class, 'show']);
    Route::post('usuarios/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/approve', [UserController::class, 'approve']);
    Route::post('usuarios/{user}/avatar', [UserController::class, 'uploadAvatar'])->name('users.avatar.upload');
    Route::delete('usuarios/{user}/avatar', [UserController::class, 'deleteAvatar'])->name('users.avatar.delete');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
    
    // 3. Notificacoes
    Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::resource('notifications', NotificationController::class)->only(['index', 'create', 'store', 'destroy']);
    
    // 4. Biblioteca
    Route::resource('biblioteca', LibraryController::class)->names('library')->parameters(['biblioteca' => 'library']);
    Route::post('biblioteca/{library}/files', [LibraryController::class, 'uploadFiles'])->name('library.files.upload');
    Route::delete('biblioteca/{library}/files/{file}', [LibraryController::class, 'deleteFile'])->name('library.files.delete');
    Route::post('biblioteca/{library}/permissions', [LibraryController::class, 'updatePermissions'])->name('library.permissions.update');
    Route::post('biblioteca/{library}/onedrive/sync', [LibraryController::class, 'syncToOneDrive'])->name('library.onedrive.sync');
    Route::post('biblioteca/{library}/onedrive/retry', [LibraryController::class, 'retryOneDriveSync'])->name('library.onedrive.retry');
    Route::resource('biblioteca-categorias', LibraryCategoryController::class)->names('library-categories')->parameters(['biblioteca-categorias' => 'library_category'])->only(['index', 'store', 'destroy']);

    // 5. Na Mídia
    Route::resource('na-midia', MediaController::class)->names('media')->parameters(['na-midia' => 'media']);
    Route::get('midia/{media}', [MediaController::class, 'show']);
    Route::post('na-midia/{media}/files', [MediaController::class, 'uploadFiles'])->name('media.files.upload');
    Route::delete('na-midia/{media}/files/{file}', [MediaController::class, 'deleteFile'])->name('media.files.delete');
    Route::post('na-midia/{media}/permissions', [MediaController::class, 'updatePermissions'])->name('media.permissions.update');
    Route::post('na-midia/{media}/onedrive/sync', [MediaController::class, 'syncToOneDrive'])->name('media.onedrive.sync');
    Route::post('na-midia/{media}/onedrive/retry', [MediaController::class, 'retryOneDriveSync'])->name('media.onedrive.retry');
    Route::resource('na-midia-categorias', MediaCategoryController::class)->names('media-categories')->parameters(['na-midia-categorias' => 'media_category'])->only(['index', 'store', 'destroy']);
    
    // 6. Treinamentos
    Route::resource('treinamentos', TrainingController::class)->names('training')->parameters(['treinamentos' => 'training']);
    Route::get('treinamento/{training}', [TrainingController::class, 'show']);
    Route::post('treinamentos/{training}/files', [TrainingController::class, 'uploadFiles'])->name('training.files.upload');
    Route::delete('treinamentos/{training}/files/{file}', [TrainingController::class, 'deleteFile'])->name('training.files.delete');
    Route::delete('treinamentos/{training}/videos/{video}', [TrainingController::class, 'deleteVideo'])->name('training.videos.delete');
    Route::post('treinamentos/{training}/permissions', [TrainingController::class, 'updatePermissions'])->name('training.permissions.update');
    Route::post('treinamentos/{training}/onedrive/sync', [TrainingController::class, 'syncToOneDrive'])->name('training.onedrive.sync');
    Route::post('treinamentos/{training}/onedrive/retry', [TrainingController::class, 'retryOneDriveSync'])->name('training.onedrive.retry');
    Route::resource('treinamentos-categorias', TrainingCategoryController::class)->names('training-categories')->parameters(['treinamentos-categorias' => 'training_category'])->only(['index', 'store', 'destroy']);
    
    // 7. Campanhas
    Route::resource('campanhas', CampaignController::class)->names('campaigns')->parameters(['campanhas' => 'campaign']);
    Route::get('campanha/{campaign}', [CampaignController::class, 'show']);
    Route::post('campanhas/{campaign}/files', [CampaignController::class, 'uploadFiles'])->name('campaigns.files.upload');
    Route::post('campanhas/{campaign}/posts', [CampaignController::class, 'storePost'])->name('campaigns.posts.store');
    Route::post('campanhas/{campaign}/folders', [CampaignController::class, 'storeFolder'])->name('campaigns.folders.store');
    Route::post('campanhas/{campaign}/videos', [CampaignController::class, 'storeVideo'])->name('campaigns.videos.store');
    Route::post('campanhas/{campaign}/miscellaneous', [CampaignController::class, 'storeMiscellaneous'])->name('campaigns.miscellaneous.store');
    Route::post('campanhas/{campaign}/onedrive/sync', [CampaignController::class, 'syncToOneDrive'])->name('campaigns.onedrive.sync');
    Route::post('campanhas/{campaign}/onedrive/retry', [CampaignController::class, 'retryOneDriveSync'])->name('campaigns.onedrive.retry');
    Route::delete('campanhas/{campaign}/posts/{postId}', [CampaignController::class, 'deletePost'])->name('campaigns.posts.delete');
    Route::delete('campanhas/{campaign}/folders/{folderId}', [CampaignController::class, 'deleteFolder'])->name('campaigns.folders.delete');
    Route::delete('campanhas/{campaign}/videos/{videoId}', [CampaignController::class, 'deleteVideo'])->name('campaigns.videos.delete');
    Route::delete('campanhas/{campaign}/miscellaneous/{miscId}', [CampaignController::class, 'deleteMiscellaneous'])->name('campaigns.miscellaneous.delete');
    
    // 8. Radar
    Route::resource('radar', NewsController::class)->names('news')->parameters(['radar' => 'news']);
    Route::get('noticia/{news}', [NewsController::class, 'show']);
    Route::post('radar/{news}/image', [NewsController::class, 'uploadImage'])->name('news.image.upload');
    Route::delete('radar/{news}/image', [NewsController::class, 'deleteImage'])->name('news.image.delete');
    Route::post('radar/{news}/permissions', [NewsController::class, 'updatePermissions'])->name('news.permissions.update');
    Route::post('radar/{news}/onedrive/sync', [NewsController::class, 'syncToOneDrive'])->name('news.onedrive.sync');
    Route::post('radar/{news}/onedrive/retry', [NewsController::class, 'retryOneDriveSync'])->name('news.onedrive.retry');
    Route::resource('radar-categorias', NewsCategoryController::class)->names('news-categories')->parameters(['radar-categorias' => 'news_category'])->only(['index', 'store', 'destroy']);
    
    // 9. Relatorios e Ajuda
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('reports/downloads', [ReportController::class, 'downloads'])->name('reports.downloads');
    Route::get('reports/files', [ReportController::class, 'files'])->name('reports.files');
    Route::get('reports/access', [ReportController::class, 'access'])->name('reports.access');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('como-usar', [HelpController::class, 'index'])->name('help.index');
    Route::get('como-usar/{topic}', [HelpController::class, 'show'])->name('help.show')->where('topic', '[a-z0-9_-]+');

    // Aliases Legados para JavaScript (Manter compatibilidade com scripts existentes)
    Route::prefix('training')->group(function() {
        Route::post('{training}/files', [TrainingController::class, 'uploadFiles']);
        Route::delete('{training}/files/{file}', [TrainingController::class, 'deleteFile']);
        Route::delete('{training}/videos/{video}', [TrainingController::class, 'deleteVideo']);
    });
    Route::prefix('campaigns')->group(function() {
        Route::delete('{campaign}/posts/{postId}', [CampaignController::class, 'deletePost']);
        Route::delete('{campaign}/folders/{folderId}', [CampaignController::class, 'deleteFolder']);
        Route::delete('{campaign}/videos/{videoId}', [CampaignController::class, 'deleteVideo']);
        Route::delete('{campaign}/miscellaneous/{miscId}', [CampaignController::class, 'deleteMiscellaneous']);
    });
    Route::prefix('products')->group(function() {
        Route::post('{product}/files', [ProductController::class, 'uploadFiles']);
        Route::delete('{product}/files/{file}', [ProductController::class, 'deleteFile']);
    });
    Route::prefix('library')->group(function() {
        Route::delete('{library}/files/{file}', [LibraryController::class, 'deleteFile']);
    });
    Route::prefix('media')->group(function() {
        Route::delete('{media}/files/{file}', [MediaController::class, 'deleteFile']);
    });
    Route::prefix('news')->group(function() {
        Route::post('{news}/image', [NewsController::class, 'uploadImage']);
    });
});
