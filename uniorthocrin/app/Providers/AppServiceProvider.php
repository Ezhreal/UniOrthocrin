<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\UiVisibilityService;
use App\Repositories\UiVisibilityRepository;
use App\Models\Product;
use App\Models\News;
use App\Models\Library;
use App\Models\Training;
use App\Observers\ProductObserver;
use App\Observers\NewsObserver;
use App\Observers\LibraryObserver;
use App\Observers\TrainingObserver;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('canSee', function ($feature) {
            return app(\App\Services\UiVisibilityService::class)->canView($feature);
        });
        
        // Register observers for automatic notifications
        Product::observe(ProductObserver::class);
        News::observe(NewsObserver::class);
        Library::observe(LibraryObserver::class);
        Training::observe(TrainingObserver::class);
    }
}
