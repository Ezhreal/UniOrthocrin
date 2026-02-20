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
use App\Http\Controllers\Admin\HelpController;

// Login admin dedicado
Route::get('admin/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
Route::post('admin/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('admin.logout');

// Redirecionamento /admin baseado no status de autenticação
Route::get('admin', function () {
    if (Auth::check()) {
        // Se estiver logado, verifica se é admin
        if (Auth::user()->user_type_id == 1) {
            return redirect()->route('admin.dashboard');
        } else {
            // Se não for admin, redireciona para home
            return redirect()->route('home');
        }
    } else {
        // Se não estiver logado, vai para login admin
        return redirect()->route('admin.login');
    }
})->name('admin.redirect');

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminOnly::class, \App\Http\Middleware\NoStoreForAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/files', [ProductController::class, 'uploadFiles'])->name('products.files.upload');
    Route::delete('products/{product}/files/{file}', [ProductController::class, 'deleteFile'])->name('products.files.delete');
    Route::post('products/{product}/permissions', [ProductController::class, 'updatePermissions'])->name('products.permissions.update');
    Route::post('products/{product}/onedrive/sync', [ProductController::class, 'syncToOneDrive'])->name('products.onedrive.sync');
    Route::post('products/{product}/onedrive/retry', [ProductController::class, 'retryOneDriveSync'])->name('products.onedrive.retry');
    
    // Product Categories
    Route::resource('product-categories', ProductCategoryController::class)->only(['index', 'store', 'destroy']);
    
    // Product Series
    Route::resource('product-series', ProductSeriesController::class)->only(['index', 'store', 'destroy']);
    
    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/avatar', [UserController::class, 'uploadAvatar'])->name('users.avatar.upload');
    Route::delete('users/{user}/avatar', [UserController::class, 'deleteAvatar'])->name('users.avatar.delete');
    
    // Admin Profile
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
    
    // Notifications
    Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::resource('notifications', NotificationController::class)->only(['index', 'create', 'store', 'destroy']);
    
    // Library
    Route::resource('library', LibraryController::class);
    Route::post('library/{library}/files', [LibraryController::class, 'uploadFiles'])->name('library.files.upload');
    Route::delete('library/{library}/files/{file}', [LibraryController::class, 'deleteFile'])->name('library.files.delete');
    Route::post('library/{library}/permissions', [LibraryController::class, 'updatePermissions'])->name('library.permissions.update');
    Route::post('library/{library}/onedrive/sync', [LibraryController::class, 'syncToOneDrive'])->name('library.onedrive.sync');
    Route::post('library/{library}/onedrive/retry', [LibraryController::class, 'retryOneDriveSync'])->name('library.onedrive.retry');
    
    // Library Categories
    Route::resource('library-categories', LibraryCategoryController::class)->only(['index', 'store', 'destroy']);
    
    // Training
    Route::resource('training', TrainingController::class);
    Route::post('training/{training}/files', [TrainingController::class, 'uploadFiles'])->name('training.files.upload');
    Route::delete('training/{training}/files/{file}', [TrainingController::class, 'deleteFile'])->name('training.files.delete');
    Route::delete('training/{training}/videos/{video}', [TrainingController::class, 'deleteVideo'])->name('training.videos.delete');
    Route::post('training/{training}/permissions', [TrainingController::class, 'updatePermissions'])->name('training.permissions.update');
    Route::post('training/{training}/onedrive/sync', [TrainingController::class, 'syncToOneDrive'])->name('training.onedrive.sync');
    Route::post('training/{training}/onedrive/retry', [TrainingController::class, 'retryOneDriveSync'])->name('training.onedrive.retry');
    
    // Training Categories
    Route::resource('training-categories', TrainingCategoryController::class)->only(['index', 'store', 'destroy']);
    
    // Campaigns
    Route::resource('campaigns', CampaignController::class);
    Route::post('campaigns/{campaign}/posts', [CampaignController::class, 'storePost'])->name('campaigns.posts.store');
    Route::post('campaigns/{campaign}/folders', [CampaignController::class, 'storeFolder'])->name('campaigns.folders.store');
    Route::post('campaigns/{campaign}/videos', [CampaignController::class, 'storeVideo'])->name('campaigns.videos.store');
    Route::post('campaigns/{campaign}/miscellaneous', [CampaignController::class, 'storeMiscellaneous'])->name('campaigns.miscellaneous.store');
    Route::post('campaigns/{campaign}/onedrive/sync', [CampaignController::class, 'syncToOneDrive'])->name('campaigns.onedrive.sync');
    Route::post('campaigns/{campaign}/onedrive/retry', [CampaignController::class, 'retryOneDriveSync'])->name('campaigns.onedrive.retry');
    
    // News
    Route::resource('news', NewsController::class);
    Route::post('news/{news}/image', [NewsController::class, 'uploadImage'])->name('news.image.upload');
    Route::delete('news/{news}/image', [NewsController::class, 'deleteImage'])->name('news.image.delete');
    Route::post('news/{news}/permissions', [NewsController::class, 'updatePermissions'])->name('news.permissions.update');
    Route::post('news/{news}/onedrive/sync', [NewsController::class, 'syncToOneDrive'])->name('news.onedrive.sync');
    Route::post('news/{news}/onedrive/retry', [NewsController::class, 'retryOneDriveSync'])->name('news.onedrive.retry');
    
    // News Categories
    Route::resource('news-categories', NewsCategoryController::class)->only(['index', 'store', 'destroy']);
    
    // Como usar (ajuda)
    Route::get('como-usar', [HelpController::class, 'index'])->name('help.index');
    Route::get('como-usar/{topic}', [HelpController::class, 'show'])->name('help.show')->where('topic', '[a-z0-9_-]+');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('reports/downloads', [ReportController::class, 'downloads'])->name('reports.downloads');
    Route::get('reports/files', [ReportController::class, 'files'])->name('reports.files');
    Route::get('reports/access', [ReportController::class, 'access'])->name('reports.access');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
});
