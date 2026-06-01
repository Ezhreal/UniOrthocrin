<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use App\Services\UiVisibilityService;
use App\Repositories\UiVisibilityRepository;
use App\Models\Product;
use App\Models\News;
use App\Models\Library;
use App\Models\Training;
use App\Models\Media;
use App\Observers\ProductObserver;
use App\Observers\NewsObserver;
use App\Observers\LibraryObserver;
use App\Observers\TrainingObserver;
use App\Observers\MediaObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UiVisibilityRepository::class, function ($app) {
            return new UiVisibilityRepository();
        });
        $this->app->singleton(UiVisibilityService::class, function ($app) {
            return new UiVisibilityService($app->make(UiVisibilityRepository::class));
        });

        // Altera o caminho público para a raiz externa (public_html) encontrar o manifesto do Vite
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $entryDir = dirname($_SERVER['SCRIPT_FILENAME']);
            $parentDir = realpath(base_path('../'));
            if ($entryDir === $parentDir) {
                $this->app->usePublicPath($parentDir);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('canSee', function ($feature) {
            return app(\App\Services\UiVisibilityService::class)->canView($feature);
        });

        // O profile_slug é agora injetado dinamicamente via o middleware SetDefaultProfileSlug
        // que roda após o início da sessão.

        // Register observers for automatic notifications
        Product::observe(ProductObserver::class);
        News::observe(NewsObserver::class);
        Library::observe(LibraryObserver::class);
        Training::observe(TrainingObserver::class);
        Media::observe(MediaObserver::class);
    }
}
