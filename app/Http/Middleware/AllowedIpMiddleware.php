<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AllowedIpMiddleware {
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response {
        // Define allowed IPs
        $allowedIps = explode(',', env('ALLOWED_IPS', ''));

        // Always allow local IPs (Swagger, internal requests)
        $localIps = ['127.0.0.1', '::1'];
        // Add your allowed IPs here

        // $requestIp = $request->ip();
        // Log the IP to storage/logs/laravel.log
        // Log::info("Request IP: " . $requestIp);

        // Get the request IP
        $requestIp = $request->ip();

        // Check if the request IP is in the allowed list
        if (!in_array($requestIp, array_merge($allowedIps, $localIps))) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        return $next($request);
    }
}
