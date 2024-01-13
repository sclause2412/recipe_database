<?php

namespace App\Livewire\Users;

use App\Actions\Livewire\CleanupInput;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class Profile extends Component
{
    use WithFileUploads;
    use CleanupInput;
    use AuthorizesRequests;
    use WireUiActions;

    public $user;
    public $name = '';
    public $email = '';
    public $firstname = '';
    public $lastname = '';

    public $photo;

    public $verificationLinkSent = false;


    public function mount($user)
    {
        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->firstname = $this->user->firstname;
        $this->lastname = $this->user->lastname;
    }

    public function render()
    {
        return view('livewire.users.profile');
    }

    public function updateProfile()
    {
        $this->authorize('update', $this->user);

        $user = $this->user;

        $this->name = strtolower($this->cleanInput($this->name));
        $this->email = $this->cleanInput($this->email);
        $this->firstname = $this->cleanInput($this->firstname);
        $this->lastname = $this->cleanInput($this->lastname);

        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->withoutTrashed()->ignore($user->id)],
            'email' => ['required', 'email', 'max:255'],
            'firstname' => ['nullable', 'string', 'max:50'],
            'lastname' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png'],
        ]);

        if (!is_null($this->photo)) {
            $user->updateProfilePhoto($this->photo);
            $this->photo = null;
        }

        $sendmail = false;
        if ($user->id == Auth::user()?->id && $this->email !== $user->email && $user instanceof MustVerifyEmail) {
            $user->email_verified_at = null;
            $sendmail = true;
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->save();


        $this->notification()->success(__('Profile saved'), __('Your profile was successfully saved'));

        if ($user->current)
            $this->dispatch('refresh-navigation-menu');

        if ($sendmail) {
            $user->sendEmailVerificationNotification();
            $this->verificationLinkSent = true;
        }
    }


    public function deleteProfilePhoto()
    {
        $this->authorize('update', $this->user);

        $this->user->deleteProfilePhoto();

        $this->photo = null;

        if ($this->user->id == Auth::user()?->id)
            $this->dispatch('refresh-navigation-menu');
    }

    public function sendEmailVerification()
    {
        $this->user->sendEmailVerificationNotification();
        $this->verificationLinkSent = true;
    }

    public function updatedPhoto()
    {
        $this->validate(['photo' => ['nullable', 'mimes:jpg,jpeg,png']]);
    }
}
