<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self';" .
                "script-src 'self' https://*.cloudfront.net https://*.midtrans.com 'unsafe-inline' 'unsafe-eval';" .
                "style-src 'self' https://*.midtrans.com 'unsafe-inline';" .
                "img-src 'self' https://*.midtrans.com data:;" .
                "connect-src 'self' https://*.midtrans.com https://api.sandbox.midtrans.com;"
        );

        return $response;
    }
}
