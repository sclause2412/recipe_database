<?php

namespace App\Livewire\Users;

use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Livewire\CleanupInput;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Create extends Component
{
    use PasswordValidationRules;
    use CleanupInput;
    use AuthorizesRequests;
    use WireUiActions;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $email_confirm = true;
    public $policy_confirm = true;
    public $active = true;

    public $photo;

    public $verificationLinkSent = false;

    public function render()
    {
        return view('livewire.users.create');
    }

    public function createUser()
    {
        $this->authorize('create', User::class);

        $this->name = strtolower($this->cleanInput($this->name));
        $this->email = $this->cleanInput($this->email);
        $this->active = $this->cleanInput($this->active);

        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->withoutTrashed()],
            'email' => ['required', 'email', 'max:255'],
            'password' => $this->passwordRulesNullable(),
        ]);

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = $this->password ? Hash::make($this->password) : null;
        $user->active = $this->active;

        if ($this->email_confirm) {
            $sendmail = true;
        } else {
            $sendmail = false;
            $user->email_verified_at = now();
        }

        if (!$this->policy_confirm) {
            $user->policy_accepted = true;
        }

        $user->save();

        $this->dispatch('saved_user');

        if ($sendmail) {

            if (is_null($this->password)) {
                Password::broker(config('fortify.passwords'))->sendResetLink(
                    ['name' => $user->name, 'email' => $user->email]
                );
            } else {
                $user->sendEmailVerificationNotification();
            }
        }

        return redirect()->route('users.index');
    }
}
