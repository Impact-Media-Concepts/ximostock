<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
        return Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): Response
    {
        return Response::deny();
    }
}
