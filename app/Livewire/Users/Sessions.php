<?php

namespace App\Livewire\Users;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Agent;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Sessions extends Component
{
    use AuthorizesRequests;
    use WireUiActions;

    public $user;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.sessions');
    }

    public function getSessionsProperty()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $this->user->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) {
            return (object) [
                'id' => $session->id,
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    protected function createAgent($session)
    {
        return tap(new Agent(), fn ($agent) => $agent->setUserAgent($session->user_agent));
    }

    public function logoutSessions()
    {
        $this->authorize('update', $this->user);

        $this->resetErrorBag();

        if (config('session.driver') !== 'database') {
            return;
        }

        $this->user->logoutEverywhere();

        $this->user->remember_token = null;
        $this->user->save();

        $this->notification()->success(__('Logged out'), __('All your sessions are closed now.'));

        if ($this->user->current) {
            return redirect()->route('login');
        }
    }

    public function logoutSession($id)
    {
        $this->resetErrorBag();
        if (!(is_elevated() || $this->user->current)) {
            throw ValidationException::withMessages([
                '-' => [__('You have no rights to execute this function.')],
            ]);
        }

        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $this->user->getAuthIdentifier())
            ->where('id', $id)
            ->delete();

        $this->user->remember_token = null;
        $this->user->save();

        $this->notification()->success(__('Logged out'), __('The session is closed now.'));

        if (request()->session()->getId() === $id) {
            return redirect()->route('login');
        }
    }
}
