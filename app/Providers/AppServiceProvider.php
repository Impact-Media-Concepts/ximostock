<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\LocationZone;
use App\Models\Product;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Models\User;
use App\View\Components\allow;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Constraint\Count;

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

        Gate::define('store-product', function(User $user, array $salesChannelIds, array $categoryIds, array $propertyIds, array $locationZoneIds){

            $salesChannels = SalesChannel::whereIn('id', $salesChannelIds)->get();
            $categories = Category::whereIn('id', $categoryIds)->get();
            $properties = Property::whereIn('id', $propertyIds)->get();
            $locationZones = LocationZone::whereIn('id', $locationZoneIds)->get();
            //dd($salesChannels,  $categories, $properties, $locationZones, $locationZoneIds);

            if(Count($salesChannelIds) != Count($salesChannels) || Count($categoryIds) != Count($categories) || Count($propertyIds) != Count($properties) || Count($locationZoneIds) != Count($locationZones)){
                //return bad request if one of the ids not found.
                return Response::denyWithStatus(400);

            }
            if($user->role === 'admin'){
                //allow if user is admin
                return Response::allow();
            }elseif($user->role === 'manager'){
                foreach($salesChannels as $salesChannel){
                    if($salesChannel->work_space_id !== $user->work_space_id){
                        return Response::deny();
                    }
                }
                foreach($categories as $category){
                    if($category->work_space_id !== $user->work_space_id){
                        return Response::deny();
                    }
                }
                foreach($properties as $property){
                    if($property->work_space_id !== $user->work_space_id){
                        return Response::deny();
                    }
                }
                foreach($locationZones as $locationZone){
                    if($locationZone->work_space_id !== $user->work_space_id){
                        return Response::deny();
                    }
                }
                return Response::allow();
            }else{
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });

        //checks if you are allowd to do bulkactions with the given products
        Gate::define('bulk-products', function(User $user, array $product_ids){
            $products = Product::whereIn('id', $product_ids)->get();
            if(Count($product_ids) != Count($products)){
                //if some product ids are wrong return bad request
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

        Gate::define('bulk-saleschannel-products', function(User $user, array $product_ids, array $sales_channel_ids){
            $products = Product::whereIn('id', $product_ids)->get();
            $salesChannels = SalesChannel::whereIn('id', $sales_channel_ids)->get();

            if(Count($product_ids) != Count($products) || Count($salesChannels) != Count($sales_channel_ids)){
                //if some product or sales channel ids are wrong return bad request
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
                foreach($salesChannels as $salesChannel){
                    if($salesChannel->work_space_id != $user->work_space_id ){
                        //a saleschannel was not part of his workspace
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
