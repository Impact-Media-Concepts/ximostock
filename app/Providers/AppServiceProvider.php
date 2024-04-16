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
