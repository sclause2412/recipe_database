<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserIsElevated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (is_null($user))
            abort(403);
        if (!$user->admin)
            abort(403);
        if (!$user->elevated)
            return redirect()->route('welcome')->with('message', ['text' => __('Please activate your Admin rights before you use this area.'), 'title' => __('Admin'), 'icon' => 'warning']);
        return $next($request);
    }
}
