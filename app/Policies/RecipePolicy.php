<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecipePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; //$user->elevated || $user->can_read('project') || has_project(null, $user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Recipe $recipe): Response
    {
        return ($recipe->active || $user?->can_read('recipe'))
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->elevated || $user->can_write('recipe');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->elevated || $user->can_write('recipe');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->elevated || $user->can_write('recipe');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recipe $recipe): bool
    {
        return $user->elevated || $user->can_write('recipe');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recipe $recipe): bool
    {
        return $user->elevated;
    }
}
