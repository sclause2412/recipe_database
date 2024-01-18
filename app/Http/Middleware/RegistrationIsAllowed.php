<?php

namespace App\Http\Middleware;

use App\Http\Controllers\GlobalSettingsController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationIsAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!GlobalSettingsController::get('register', true)) {
            abort(403);
        }

        return $next($request);
    }
}
