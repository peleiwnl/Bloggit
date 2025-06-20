<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Observers\UserObserver;
use App\Services\NewsService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsService::class, function ($app) {
            return NewsService::getInstance();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        View::composer('partials.infobar', function ($view) {
            $view->with('admins', User::where('role', 'admin')->with('profile')->get());
        });

        View::composer('partials.popular-posts', function ($view) {
            $popularPosts = Post::withCount('comments')
                ->has('comments')
                ->with('user')
                ->orderByDesc('comments_count')
                ->limit(10)
                ->get();
            
            $view->with('popularPosts', $popularPosts);
        });
    }
}
