<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\LocationZone;
use App\Models\Product;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    //for all actions. if the user is an admin allow the action.
    public function before(User $user, string $ability): Response|null
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        if ($user->role === 'manager') {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): Response
    {
        if ($user->role === 'manager' && $user->work_space_id === $product->work_space_id && $product->parent_product_id === null) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can go to the create page.
     */
    public function create(User $user): Response
    {
        if ($user->role === 'manager') {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can create a product and link the given saleschannels, categories, products and locationzones
     */
    public function store(User $user, array $salesChannelIds, array $categoryIds, array $propertyIds, array $locationZoneIds): Response
    {
        $salesChannels = SalesChannel::whereIn('id', $salesChannelIds)->get();
        $categories = Category::whereIn('id', $categoryIds)->get();
        $properties = Property::whereIn('id', $propertyIds)->get();
        $locationZones = LocationZone::whereIn('id', $locationZoneIds)->get();

        if (Count($salesChannelIds) != Count($salesChannels) || Count($categoryIds) != Count($categories) || Count($propertyIds) != Count($properties) || Count($locationZoneIds) != Count($locationZones)) {
            //return bad request if one of the ids not found.
            return Response::denyWithStatus(400);
        }

        if ($user->role === 'manager') {
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
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product, array $salesChannelIds, array $categoryIds, array $propertyIds, array $locationZoneIds): Response
    {
        $salesChannels = SalesChannel::whereIn('id', $salesChannelIds)->get();
        $categories = Category::whereIn('id', $categoryIds)->get();
        $properties = Property::whereIn('id', $propertyIds)->get();
        $locationZones = LocationZone::whereIn('id', $locationZoneIds)->get();
        // dd($salesChannels, $categories, $properties, $locationZones);
        if (Count($salesChannelIds) != Count($salesChannels) || Count($categoryIds) != Count($categories) || Count($propertyIds) != Count($properties) || Count($locationZoneIds) != Count($locationZones)) {
            //return bad request if one of the ids not found.
            return Response::denyWithStatus(400);
        }
        if ($user->role === 'manager' && $product->work_space_id === $user->work_space_id) {
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
    }

    /**
     * Determine whether the user can bulk delete the model.
     */
    public function bulkDelete(User $user, array $product_ids): Response
    {
        $products = Product::whereIn('id', $product_ids)->get();
        if (Count($product_ids) != Count($products)) {
            //if some product ids are wrong return bad request
            return Response::denyWithStatus(400);
        }

        if ($user->role === 'manager') {
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
    }

    /**
     * Determine whether the user can bulk update the model.
     */
    public function bulkUpdate(User $user, array $product_ids): Response
    {
        $products = Product::whereIn('id', $product_ids)->get();
        if (Count($product_ids) != Count($products)) {
            //if some product ids are wrong return bad request
            return Response::denyWithStatus(400);
        }

        if ($user->role === 'manager') {
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
    }

    /**
     * Determine whether the user can bulk link or unlink saleschannels to the model
     */
    public function bulkSaleschannels(User $user, array $product_ids, array $sales_channel_ids): Response
    {
        $products = Product::whereIn('id', $product_ids)->get();
        $salesChannels = SalesChannel::whereIn('id', $sales_channel_ids)->get();

        if (Count($product_ids) != Count($products) || Count($salesChannels) != Count($sales_channel_ids)) {
            //if some product or sales channel ids are wrong return bad request
            return Response::denyWithStatus(400);
        }
        if ($user->role === 'manager') {
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
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): Response
    {
        if ($user->role === 'manager' && $user->work_space_id === $product->work_space_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): Response
    {
        return Response::deny();
    }
}
