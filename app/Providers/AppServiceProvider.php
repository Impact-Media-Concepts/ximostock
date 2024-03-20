<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use App\View\Components\allow;
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

        
        Gate::define('view-product', function(User $user, Product $product){
            if($user->role === 'admin'){
                //allow if admin
                return Response::allow();
            }elseif($user->role === 'manager'){
                if($user->work_space_id === $product->work_space_id) {
                    //allow if product is part of managers workspace
                    return Response::allow();
                }else{
                    //deny if product is not part of managers workspace
                    return Response::deny();
                }
            }
            else{
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });

        Gate::define('create-product', function(User $user){
            if($user->role === 'admin' || $user->role === 'manager') {
                //allow if user is an adin or manager otherwise deny
                return Response::allow();
            }else{
                return Response::deny();
            }
        });

        Gate::define('destroy-product', function(User $user, Product $product) {
            if($user->role === 'admin'){
                //allow if user is admin
                return Response::allow();
            }elseif($user->role === 'manager' && $user->work_space_id === $product->work_space_id){
                //allow if user is manager and product is part of his workspace
                return Response::allow();
            }else{
                return Response::deny();
            }
        });

        Gate::define('update-product', function(User $user, Product $product) {
            if($user->role === 'admin'){
                //allow if user is admin
                return Response::allow();
            }elseif($user->role === 'manager' && $user->work_space_id === $product->work_space_id){
                //allow if user is manager and product is part of his workspace
                return Response::allow();
            }else{
                return Response::deny();
            } 
        });

        Gate::define('bulk-products', function(User $user, array $product_ids){
            $products = Product::whereIn('id', $product_ids)->get();
            if(Count($product_ids) != Count($products)){
                return Response::denyWithStatus(400);
            }
            if($user->role === 'admin'){
                //allow if user is admin
                return Response::allow();
            }elseif($user->role === 'manager'){
                foreach($products as $product){
                    if($product->work_space_id != $user->work_space_id){
                        //a product was not part of his workspace
                        return Response::deny();
                    }
                }
                //return true if all manager and all products are part of his workspace
                return Response::allow();
            }else{
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });
    }
}
