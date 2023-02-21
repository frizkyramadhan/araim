<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {

        if (empty($roles)) $roles = ['admin'];

        foreach ($roles as $role) {
            if (Auth::check() && Auth::user()->level === $role) {
                return $next($request);
            } elseif (Auth::check() && Auth::user()->level === null) {
                return redirect('login');
            }
        }
        return response()->view('errors.403', ['title' => '403 Error']);
    }
}
