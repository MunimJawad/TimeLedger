<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Policies\CommentPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\Comment;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        Schema::defaultStringLength(191);
        Gate::policy(Comment::class,CommentPolicy::class);
        
        View::composer('*',function($view){
          $user=Auth::user();
          $unreadCount=$user?$user->unreadNotifications->count() : 0;
          $view->with('unreadNotificationsCount', $unreadCount);
        });
    }
}
