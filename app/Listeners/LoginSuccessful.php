<?php

namespace App\Listeners;

use App\Http\Controllers\ThemeController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginSuccessful
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if (Auth::user()->single_login) {
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', Auth::user()->getAuthIdentifier())
                ->where('id', '!=', request()->session()->getId())
                ->delete();
        }

        ThemeController::load_user_settings();
    }
}
