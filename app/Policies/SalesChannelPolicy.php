<?php

namespace App\Policies;

use App\Models\SalesChannel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalesChannelPolicy
{
    //for all actions. if the user is an admin allow the action.
    public function before(User $user, string $ability): Response|null{
        if($user->role === 'admin'){
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
    public function view(User $user, SalesChannel $salesChannel): Response
    {
        if ($user->role === 'manager' && $user->work_space_id === $salesChannel->work_space_id) {
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
    public function update(User $user, SalesChannel $salesChannel): Response
    {
        if ($user->role === 'manager' && $user->work_space_id === $salesChannel->work_space_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SalesChannel $salesChannel): Response
    {
        if ($user->role === 'manager' && $user->work_space_id === $salesChannel->work_space_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SalesChannel $salesChannel): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SalesChannel $salesChannel): Response
    {

        return Response::deny();
    }
}
