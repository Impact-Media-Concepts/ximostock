<?php

namespace App\Providers;


use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;


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
        Paginator::useTailwind();

        //workspace authorization
        Gate::define('index-workspaces', function(User $user){
            if($user->role === 'admin'){
                return Response::allow();
            }else{
                return Response::deny();
            }
        });
    }
}
