<?php

namespace App\Livewire\Users;

use App\Overrides\ConfirmsPasswords;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Delete extends Component
{
    use ConfirmsPasswords;
    use AuthorizesRequests;

    public $user;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.delete');
    }

    public function deleteUser()
    {
        $this->authorize('delete', $this->user);

        $this->resetErrorBag();

        $user = $this->user;
        if ($user->current) {
            $this->ensurePasswordIsConfirmed();

            if (is_last_admin($user)) {
                throw ValidationException::withMessages([
                    '-' => [__('You cannot delete your account as you are the last admin.')],
                ]);
            }

            if (request()->hasSession()) {
                request()->session()->invalidate();
                request()->session()->regenerateToken();
            }
        }

        $user->rights()->delete();
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();

        if ($user->current) {
            return redirect()->route('login');
        } else {
            return redirect()->route('users.index');
        }
    }
}
