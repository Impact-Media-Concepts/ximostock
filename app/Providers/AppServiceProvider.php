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
use Ramsey\Uuid\Type\Integer;

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


        //product authorization
        Gate::define('view-product', function (User $user, Product $product) {
            if ($user->role === 'admin') {
                //allow if admin
                return Response::allow();
            } elseif ($user->role === 'manager') {
                if ($user->work_space_id === $product->work_space_id) {
                    //allow if product is part of managers workspace
                    return Response::allow();
                } else {
                    //deny if product is not part of managers workspace
                    return Response::deny();
                }
            } else {
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });

        Gate::define('create-product', function (User $user) {
            if ($user->role === 'admin' || $user->role === 'manager') {
                //allow if user is an adin or manager otherwise deny
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('destroy-product', function (User $user, Product $product) {
            if ($user->role === 'admin') {
                //allow if user is admin
                return Response::allow();
            } elseif ($user->role === 'manager' && $user->work_space_id === $product->work_space_id) {
                //allow if user is manager and product is part of his workspace
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('store-product', function (User $user, array $salesChannelIds, array $categoryIds, array $propertyIds, array $locationZoneIds) {

            $salesChannels = SalesChannel::whereIn('id', $salesChannelIds)->get();
            $categories = Category::whereIn('id', $categoryIds)->get();
            $properties = Property::whereIn('id', $propertyIds)->get();
            $locationZones = LocationZone::whereIn('id', $locationZoneIds)->get();

            if (Count($salesChannelIds) != Count($salesChannels) || Count($categoryIds) != Count($categories) || Count($propertyIds) != Count($properties) || Count($locationZoneIds) != Count($locationZones)) {
                //return bad request if one of the ids not found.
                return Response::denyWithStatus(400);
            }
            if ($user->role === 'admin') {
                //allow if user is admin
                return Response::allow();
            } elseif ($user->role === 'manager') {
                foreach ($salesChannels as $salesChannel) {
                    if ($salesChannel->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                foreach ($categories as $category) {
                    if ($category->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                foreach ($properties as $property) {
                    if ($property->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                foreach ($locationZones as $locationZone) {
                    if ($locationZone->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                return Response::allow();
            } else {
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });

        //checks if you are allowd to do bulkactions with the given products
        Gate::define('bulk-products', function (User $user, array $product_ids) {
            $products = Product::whereIn('id', $product_ids)->get();
            if (Count($product_ids) != Count($products)) {
                //if some product ids are wrong return bad request
                return Response::denyWithStatus(400);
            }
            if ($user->role === 'admin') {
                //allow if user is admin
                return Response::allow();
            } elseif ($user->role === 'manager') {
                foreach ($products as $product) {
                    if ($product->work_space_id != $user->work_space_id) {
                        //a product was not part of his workspace
                        return Response::deny();
                    }
                }
                //return true if all manager and all products are part of his workspace
                return Response::allow();
            } else {
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });

        Gate::define('bulk-saleschannel-products', function (User $user, array $product_ids, array $sales_channel_ids) {
            $products = Product::whereIn('id', $product_ids)->get();
            $salesChannels = SalesChannel::whereIn('id', $sales_channel_ids)->get();

            if (Count($product_ids) != Count($products) || Count($salesChannels) != Count($sales_channel_ids)) {
                //if some product or sales channel ids are wrong return bad request
                return Response::denyWithStatus(400);
            }
            if ($user->role === 'admin') {
                //allow if user is admin
                return Response::allow();
            } elseif ($user->role === 'manager') {
                foreach ($products as $product) {
                    if ($product->work_space_id != $user->work_space_id) {
                        //a product was not part of his workspace
                        return Response::deny();
                    }
                }
                foreach ($salesChannels as $salesChannel) {
                    if ($salesChannel->work_space_id != $user->work_space_id) {
                        //a saleschannel was not part of his workspace
                        return Response::deny();
                    }
                }
                //return true if all manager and all products are part of his workspace
                return Response::allow();
            } else {
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });

        Gate::define('update-product', function (User $user, Product $product, array $salesChannelIds, array $categoryIds, array $propertyIds, array $locationZoneIds) {
            $salesChannels = SalesChannel::whereIn('id', $salesChannelIds)->get();
            $categories = Category::whereIn('id', $categoryIds)->get();
            $properties = Property::whereIn('id', $propertyIds)->get();
            $locationZones = LocationZone::whereIn('id', $locationZoneIds)->get();

            if (Count($salesChannelIds) != Count($salesChannels) || Count($categoryIds) != Count($categories) || Count($propertyIds) != Count($properties) || Count($locationZoneIds) != Count($locationZones)) {
                //return bad request if one of the ids not found.
                return Response::denyWithStatus(400);
            }
            if ($user->role === 'admin') {
                //allow if user is admin
                return Response::allow();
            } elseif ($user->role === 'manager' && $product->work_space_id === $user->work_space_id) {
                foreach ($salesChannels as $salesChannel) {
                    if ($salesChannel->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                foreach ($categories as $category) {
                    if ($category->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                foreach ($properties as $property) {
                    if ($property->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                foreach ($locationZones as $locationZone) {
                    if ($locationZone->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                return Response::allow();
            } else {
                //deny if user is neither admin or manager
                return Response::deny();
            }
        });

        //category authorization
        Gate::define('index-category', function (User $user) {
            if ($user->role === 'admin' || $user->role === 'manager') {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('create-category', function (User $user) {
            if ($user->role === 'admin' || $user->role === 'manager') {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('destroy-category', function (User $user, Category $category) {
            if ($user->role === 'admin' || ($user->role === 'manager'  && $user->work_space_id === $category->work_space_id)) {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('show-category', function (User $user, Category $category) {
            if ($user->role === 'admin' || ($user->role === 'manager' && $user->work_space_id === $category->work_space_id)) {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('store-category', function (User $user, ?int $parentCategoryId) {
            $parentCategory = null;
            if ($parentCategoryId !== null) {
                $parentCategory = Category::where('id', $parentCategoryId)->get()->first();
                if ($parentCategory === null) {
                    return Response::denyWithStatus(400); //return bad request if given parent category does not exist
                }
            }

            if ($user->role === 'admin') {
                return Response::allow(); //allow admin reguardless of workspace
            } elseif ($user->role === 'manager') {
                if ($parentCategory) {
                    if ($parentCategory->work_space_id === $user->work_space_id) {
                        return Response::allow(); //allow if parent category is part of users workspace
                    } else {
                        return Response::deny(); //deny if not
                    }
                } else {
                    return Response::allow(); // allow if no parentategory
                }
            } else {
                return Response::deny(); //deny if user does not have approapriate role.
            }
        });

        Gate::define('update-category', function (User $user, Category $category, $parentCategoryId) {
            $parentCategory = null;
            if ($parentCategoryId !== null) {
                $parentCategory = Category::where('id', $parentCategoryId)->get()->first();
                if ($parentCategory === null) {
                    return Response::denyWithStatus(400);
                }
            }

            if ($user->role === 'admin') {
                return Response::allow();
            } elseif ($user->role === 'manager' && $category->work_space_id === $user->work_space_id) {
                if ($parentCategory) {
                    if ($parentCategory->work_space_id === $user->work_space_id) {
                        return Response::allow();
                    } else {
                        return Response::deny();
                    }
                } else {
                    return Response::allow();
                }
            } else {
                return Response::deny();
            }
        });

        //property authorization
        Gate::define('index-property', function (User $user) {
            if ($user->role === 'admin' || $user->role === 'manager') {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('show-property', function (User $user, Property $property) {
            if ($user->role === 'admin') {
                return Response::allow();
            } elseif ($user->role === 'manager' && $user->work_space_id === $property->work_space_id) {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('update-property', function (User $user, Property $property) {
            if ($user->role === 'admin' || ($user->role === 'manager' && $property->work_space_id === $user->work_space_id)) {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('bulk-property', function (User $user, ?array $propertyIds) {
            $properties = Property::whereIn('id', $propertyIds)->get();
            if (Count($properties) !== Count($propertyIds)) {
                return Response::denyWithStatus(400);
            }

            if ($user->role === 'admin') {
                return Response::allow();
            } elseif ($user->role === 'manager') {
                foreach ($properties as $property) {
                    if ($property->work_space_id !== $user->work_space_id) {
                        return Response::deny();
                    }
                }
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

        Gate::define('create-property', function(User $user){
            if ($user->role === 'admin' || $user->role === 'manager') {
                return Response::allow();
            } else {
                return Response::deny();
            }
        });

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
