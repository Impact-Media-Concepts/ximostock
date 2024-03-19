<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
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

        
        Gate::define('view-product', function(User $user, Product $product){
            if($user->role === 'admin'){
                return true;
            }elseif($user->role === 'manager'){
                return $user->work_space_id === $product->work_space_id;
            }
            else{
                return false;
            }
        });

        Gate::define('create-product', function(User $user){
            return $user->role === 'admin' || $user->role === 'manager';
        });

        Gate::define('destroy-product', function(User $user, Product $product) {
            if($user->role === 'admin'){
                return true;
            }elseif($user->role === 'manager'){
                return $user->work_space_id === $product->work_space_id;
            }else{
                return false;
            }
        });
    }
}
