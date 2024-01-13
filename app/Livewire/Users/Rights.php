<?php

namespace App\Livewire\Users;

use App\Constants\RightConstants;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Rights extends Component
{
    use AuthorizesRequests;
    use WireUiActions;

    public $user;
    public $admin = false;
    public $active = false;
    public $single_login = false;
    public $rights = [];

    public function mount($user)
    {
        $this->user = $user;
        $this->admin = $this->user->admin;
        $this->active = $this->user->active;
        $this->single_login = $this->user->single_login;
        foreach (RightConstants::scope('G')->values() as $right)
            $this->rights[$right] = $this->user->get_right($right);
    }

    public function render()
    {
        return view('livewire.users.rights');
    }

    public function updateRights()
    {
        $this->authorize('update_rights', $this->user);

        $this->resetErrorBag();

        if (is_last_admin($this->user)) {
            $this->validate([
                'admin' => ['required', 'accepted'],
                'active' => ['required', 'accepted']
            ]);
        }

        $old_single_login = $this->user->single_login;
        $this->user->admin = $this->admin;
        $this->user->active = $this->active;
        $this->user->single_login = $this->single_login;
        $this->user->save();

        foreach (RightConstants::scope('G')->values() as $right)
            $this->user->set_right($right, $this->rights[$right]);

        $this->notification()->success(__('Rights saved'), __('Rights are updated'));

        if ($old_single_login == false && $this->single_login == true) {
            $this->user->logoutEverywhere();
        }
    }
}
