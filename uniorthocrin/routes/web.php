<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserAccountController;

// Autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/esqueci-senha', [PasswordResetController::class, 'showRequestForm'])->name('password.request')->middleware('guest');
Route::post('/esqueci-senha', [PasswordResetController::class, 'sendRandomPassword'])->name('password.email')->middleware('guest');
Route::get('/cadastro/{profile?}', [RegisterController::class, 'showForm'])->name('register.profile')->middleware('guest');
Route::post('/cadastro', [RegisterController::class, 'store'])->name('register.profile.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rota raiz
Route::get('/', function (Request $request) {
    if (Auth::check()) {
        $profile = app(\App\Services\ProfileService::class)->getActiveProfile();
        $slug = $profile->slug ?? 'admin';
        if (Auth::user()->isAdmin() && $slug == 'admin') return redirect()->route('admin.dashboard');
        return redirect()->route('profile.index', ['profile_slug' => $slug]);
    }
    return redirect()->route('login');
})->name('home');

require __DIR__.'/admin.php';

// Grupo de Rotas de Perfil (User side)
Route::prefix('{profile_slug}')->middleware(['auth', \App\Http\Middleware\CheckProfileContext::class, \App\Http\Middleware\NoStoreForAuthenticated::class])->group(function () {
    
    Route::get('/index', [DashboardController::class, 'index'])->name('profile.index');

    // Produtos
    Route::get('/produtos-list', [ProductController::class, 'index'])->name('produtos.list');
    Route::get('/produtos/{id}', [ProductController::class, 'show'])->name('produtos.detail');

    // Treinamentos
    Route::get('/treinamentos-list', [TrainingController::class, 'index'])->name('treinamentos.list');
    Route::get('/treinamentos/{id}', [TrainingController::class, 'show'])->name('treinamentos.detail');

    // Campanhas (Marketing)
    Route::get('/campanhas-list', [MarketingController::class, 'index'])->name('campanhas.list');
    Route::get('/campanhas/{id}', [MarketingController::class, 'show'])->name('campanhas.detail');
    Route::post('/campanhas/{id}/download', [MarketingController::class, 'downloadCampaign'])->name('campanhas.download');
    Route::post('/campanhas/{id}/download/{type}', [MarketingController::class, 'downloadByType'])->name('campanhas.download.type');

    // Biblioteca
    Route::get('/biblioteca-list', [LibraryController::class, 'index'])->name('biblioteca.list');
    Route::get('/biblioteca/{id}', [LibraryController::class, 'show'])->name('biblioteca.detail');

    // Na Mídia
    Route::get('/na-midia-list', [MediaController::class, 'index'])->name('media.list');
    Route::get('/na-midia/{id}', [MediaController::class, 'show'])->name('media.detail');

    // Radar (News)
    Route::get('/radar-list', [NewsController::class, 'index'])->name('radar.list');
    Route::get('/radar/{id}', [NewsController::class, 'show'])->name('radar.detail');

    // Busca e Conta
    Route::get('/resultados', [SearchController::class, 'index'])->name('search.results');
    Route::get('/my-account', [UserAccountController::class, 'index'])->name('my.account');
    Route::post('/my-account/profile', [UserAccountController::class, 'updateProfile'])->name('my.account.profile');
    Route::post('/my-account/password', [UserAccountController::class, 'updatePassword'])->name('my.account.password');

    // Arquivos Privados sob o contexto do perfil ativo
    Route::get('/private/{path}', function($profile_slug, $path) {
        \Illuminate\Support\Facades\Log::info('Route A Private File access:', [
            'profile_slug' => $profile_slug,
            'path' => $path,
            'file_exists_local' => file_exists(storage_path('app/private/' . $path)),
        ]);
        if (!Auth::check()) abort(401);
        $filePath = storage_path('app/private/' . $path);
        if (!file_exists($filePath)) {
            $filePath = storage_path('app/downloads/' . $path);
            if (!file_exists($filePath)) {
                // Tentar ler do FTP
                $fullPath = 'private/' . $path;
                try {
                    if (Storage::disk('ftp')->exists($fullPath)) {
                        $mimeType = Storage::disk('ftp')->mimeType($fullPath) ?? 'application/octet-stream';
                        return response()->stream(function() use ($fullPath) {
                            $stream = Storage::disk('ftp')->readStream($fullPath);
                            if ($stream) {
                                fpassthru($stream);
                                if (is_resource($stream)) {
                                    fclose($stream);
                                }
                            }
                        }, 200, [
                            'Content-Type' => $mimeType,
                            'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error("Erro ao ler do FTP na rota private context: " . $e->getMessage());
                }
                abort(404);
            }
        }
        return response()->file($filePath);
    })->where('path', '.*')->name('profile.private.file');
});

// Rotas Utilitárias
Route::middleware(['auth'])->group(function () {
    Route::post('/download', [DownloadController::class, 'download'])->name('download.files');
    Route::get('/private/{path}', function($path) {
        if (!Auth::check()) abort(401);
        $filePath = storage_path('app/private/' . $path);
        if (!file_exists($filePath)) {
            $filePath = storage_path('app/downloads/' . $path);
            if (!file_exists($filePath)) {
                // Tentar ler do FTP
                $fullPath = 'private/' . $path;
                try {
                    if (Storage::disk('ftp')->exists($fullPath)) {
                        $mimeType = Storage::disk('ftp')->mimeType($fullPath) ?? 'application/octet-stream';
                        return response()->stream(function() use ($fullPath) {
                            $stream = Storage::disk('ftp')->readStream($fullPath);
                            if ($stream) {
                                fpassthru($stream);
                                if (is_resource($stream)) {
                                    fclose($stream);
                                }
                            }
                        }, 200, [
                            'Content-Type' => $mimeType,
                            'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error("Erro ao ler do FTP na rota private global: " . $e->getMessage());
                }
                abort(404);
            }
        }
        return response()->file($filePath);
    })->where('path', '.*');

    Route::prefix('notifications')->group(function () {
        Route::get('/dropdown', [NotificationController::class, 'getDropdownNotifications'])->name('notifications.dropdown');
        Route::get('/user', [NotificationController::class, 'getUserNotifications'])->name('notifications.user');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::delete('/delete', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');
    });
});

