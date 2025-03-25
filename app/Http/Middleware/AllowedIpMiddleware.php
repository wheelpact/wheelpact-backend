<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowedIpMiddleware {
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response {
        // Define allowed IPs
        $allowedIps = explode(',', env('ALLOWED_IPS', ''));
        // Add your allowed IPs here

        // Get the request IP
        $requestIp = $request->ip();

        // Check if IP is in the allowed list
        if (!in_array($requestIp, $allowedIps)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        return $next($request);
    }
}
