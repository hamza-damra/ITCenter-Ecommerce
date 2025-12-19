<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BootstrapIpAllowlist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = config('bootstrap.allowed_ips', []);

        // If no IPs configured, allow all
        if (empty($allowedIps)) {
            return $next($request);
        }

        $clientIp = $request->ip();

        // Check if client IP is in allowlist
        if (!in_array($clientIp, $allowedIps)) {
            abort(403, 'Access denied. Your IP address is not allowed to access Bootstrap Mode.');
        }

        return $next($request);
    }
}

