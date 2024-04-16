<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
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
    public function view(User $user, Property $property): Response
    {
        if ($user->role === 'manager' && $user->work_space_id === $property->work_space_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can create models.
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
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): Response
    {
        if ($user->role === 'manager' && $property->work_space_id === $user->work_space_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): Response
    {
        if ($user->role === 'manager' && $property->work_space_id === $user->work_space_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can bulk delete the model.
     */
    public function bulkDelete(User $user, ?array $propertyIds)
    {
        $properties = Property::whereIn('id', $propertyIds)->get();
        if (Count($properties) !== Count($propertyIds)) {
            return Response::denyWithStatus(400);
        }

        if ($user->role === 'manager') {
            foreach ($properties as $property) {
                if ($property->work_space_id !== $user->work_space_id) {
                    return Response::deny();
                }
            }
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Property $property): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Property $property): Response
    {
        return Response::deny();
    }
}
