<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
            if ($request->user()->level === $role) {
                return $next($request);
            }
        }
        return response()->view('errors.403', ['title' => '403 Error']);
    }
}
