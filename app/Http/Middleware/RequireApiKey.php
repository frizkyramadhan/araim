<?php

namespace App\Http\Middleware;

use Closure;

class RequireApiKey
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $configured = config('services.api.key');
        if (!is_string($configured) || $configured === '') {
            return response()->json([
                'message' => 'API key is not configured.',
            ], 503);
        }

        $provided = $request->header('X-API-Key');
        if (!is_string($provided) || !hash_equals($configured, $provided)) {
            return response()->json([
                'message' => 'Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
