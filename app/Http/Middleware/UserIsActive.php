<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()?->user();
        if (!is_null($user)) {
            if (!$user->active) {
                return redirect()->route('locked');
            }
            if (!$user->policy_accepted) {
                return redirect()->route('policy');
            }
        }
        return $next($request);
    }
}
