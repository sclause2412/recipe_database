<?php

namespace App\Policies;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TranslationPolicy
{
    public function viewAny(?User $user): Response
    {
        return $user->can_read('translate')
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function view(User $user, ?Translation $translation = null): bool
    {
        return $user->can_read('translate');
    }

    public function update(User $user, ?Translation $translation = null): bool
    {
        return $user->can_write('translate');
    }

    public function delete(User $user, ?Translation $translation = null): bool
    {
        return $user->can_write('translate');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ?Translation $translation = null): bool
    {
        return $user->elevated;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ?Translation $translation = null): bool
    {
        return $user->elevated;
    }
}
