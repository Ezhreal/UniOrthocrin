<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserAccountController;

// Rotas de Autenticação (sem middleware de autenticação)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rota raiz - home do site (logado ou não)
Route::get('/', function (Request $request) {
    if (Auth::check()) {
        // Se estiver logado, mostra a home do usuário
        return app(DashboardController::class)->index($request);
    } else {
        // Se não estiver logado, redireciona para login
        return redirect()->route('login');
    }
})->name('home');

// Rota de teste para debug
Route::get('/test-csrf', function() {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_status' => session()->isStarted() ? 'started' : 'not_started'
    ]);
});


// Rota de teste POST para verificar CSRF
Route::post('/test-csrf-post', function() {
    return response()->json([
        'success' => true,
        'message' => 'CSRF funcionando!',
        'session_id' => session()->getId()
    ]);
});

// Página de teste CSRF
Route::get('/test-csrf-page', function() {
    return view('test-csrf');
});

// Rotas protegidas por autenticação
Route::middleware(['auth', \App\Http\Middleware\NoStoreForAuthenticated::class])->group(function () {
    // Rotas do usuário comum (home já está na raiz)

    // Rotas de Produtos
    Route::get('/produtos-list', [ProductController::class, 'index'])->name('produtos.list');
    Route::get('/produtos/{id}', [ProductController::class, 'show'])->name('produtos.detail');

    // Rotas de Treinamentos
    Route::get('/treinamentos-list', [TrainingController::class, 'index'])->name('treinamentos.list');
    Route::get('/treinamentos/{id}', [TrainingController::class, 'show'])->name('treinamentos.detail');

    // Rotas de News
    Route::get('/news-list', [NewsController::class, 'index'])->name('news.list');

    // Rotas de Marketing (Campanhas)
    Route::get('/marketing-list', [MarketingController::class, 'index'])->name('marketing.list');
    Route::get('/marketing/{id}', [MarketingController::class, 'show'])->name('marketing.detail');
    Route::post('/marketing/{id}/download', [MarketingController::class, 'downloadCampaign'])->name('marketing.download');
    Route::post('/marketing/{id}/download/{type}', [MarketingController::class, 'downloadByType'])->name('marketing.download.type');

    // Rotas de Biblioteca
    Route::get('/biblioteca-list', [LibraryController::class, 'index'])->name('biblioteca.list');

    // Rotas de News
    Route::get('/news/{id}', function ($id) {
        return view('news-detail', ['id' => $id]);
    })->name('news.detail');

    // Rota de My Account
    Route::get('/my-account', [UserAccountController::class, 'index'])->name('my.account');
    Route::post('/my-account/profile', [UserAccountController::class, 'updateProfile'])->name('my.account.profile');
    Route::post('/my-account/password', [UserAccountController::class, 'updatePassword'])->name('my.account.password');

    // Rota para downloads
    Route::post('/download', [DownloadController::class, 'download'])->name('download.files');

    // Rota para servir arquivos privados
    Route::get('/private/{path}', function($path) {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            abort(401, 'Acesso negado');
        }
        
        // Construir o caminho completo
        $fullPath = 'private/' . $path;
        $filePath = storage_path('app/' . $fullPath);
        
        // Se não existir em private/, tentar em downloads/
        if (!file_exists($filePath)) {
            $downloadsPath = 'downloads/' . $path;
            $downloadsFilePath = storage_path('app/' . $downloadsPath);
            if (file_exists($downloadsFilePath)) {
                $filePath = $downloadsFilePath;
                $fullPath = $downloadsPath;
            } else {
                abort(404, 'Arquivo não encontrado');
            }
        }

        // Obter informações do arquivo
        $mimeType = mime_content_type($filePath);
        $fileName = basename($path);

        // Para arquivos ZIP, usar attachment para download
        $disposition = 'attachment';
        if (str_contains($mimeType, 'image') || str_contains($mimeType, 'video')) {
            $disposition = 'inline';
        }

        // Retornar o arquivo usando streaming
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => $disposition . '; filename="' . $fileName . '"',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    })->where('path', '.*')->middleware('auth');

    // Rotas de Notificações
    Route::prefix('notifications')->group(function () {
        Route::get('/dropdown', [NotificationController::class, 'getDropdownNotifications'])->name('notifications.dropdown');
        Route::get('/user', [NotificationController::class, 'getUserNotifications'])->name('notifications.user');
        Route::get('/stats', [NotificationController::class, 'getNotificationStats'])->name('notifications.stats');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        
        Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/mark-multiple-read', [NotificationController::class, 'markMultipleAsRead'])->name('notifications.mark-multiple-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        
        Route::delete('/delete', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');
        Route::delete('/delete-multiple', [NotificationController::class, 'deleteMultipleNotifications'])->name('notifications.delete-multiple');
    });
});

// Incluir rotas do admin
require __DIR__.'/admin.php';
