<?php

namespace App\Livewire\Users;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Password extends Component
{
    use PasswordValidationRules;
    use AuthorizesRequests;
    use WireUiActions;

    public $user;
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';


    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.password');
    }

    public function updatePassword()
    {
        $this->authorize('update', $this->user);

        $this->resetErrorBag();
        $this->validate([
            'current_password' => $this->user->current ? ['required', 'string', 'current_password:web'] : [],
            'password' => $this->passwordRules(),
        ]);


        $this->user->password = Hash::make($this->password);
        $this->user->save();

        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';


        $this->notification()->success(__('Password saved'), __('Your password was successfully updated'));

        if ($this->user->current) {
            return redirect()->route('login');
        }
    }
}
