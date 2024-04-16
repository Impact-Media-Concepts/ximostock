<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
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
    public function view(User $user, Category $category): Response
    {
        if ($user->role === 'manager' && $user->work_space_id === $category->work_space_id) {
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
     * Determine whether the user can create models
     * and link them to the given parent category.
     */
    public function store(User $user, ?int $parentCategoryId): Response
    {
        $parentCategory = null;
        if ($parentCategoryId !== null) {
            $parentCategory = Category::where('id', $parentCategoryId)->get()->first();
            if ($parentCategory === null) {
                return Response::denyWithStatus(400); //return bad request if given parent category does not exist
            }
        }

        if ($user->role === 'manager') {
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
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category, ?int $parentCategoryId): Response
    {
        $parentCategory = null;
        if ($parentCategoryId !== null) {
            $parentCategory = Category::where('id', $parentCategoryId)->get()->first();
            if ($parentCategory === null) {
                return Response::denyWithStatus(400);
            }
        }

        if ($user->role === 'manager' && $category->work_space_id === $user->work_space_id) {
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
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): Response
    {
        if ($user->role === 'manager'  && $user->work_space_id === $category->work_space_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): Response
    {
        return Response::deny();
    }
}
