<?php

namespace App\Http\Middleware;

use Closure;

class InjectCsrfTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->routeIs('l5-swagger.*')) {
            $swaggerJson = $response->original;

            // Check if the 'components' key is present
            if (isset($swaggerJson['components'])) {
                // Add the CSRF token security scheme
                $swaggerJson['components']['securitySchemes']['csrf_token'] = [
                    'type' => 'apiKey',
                    'name' => 'X-CSRF-TOKEN',
                    'in' => 'header',
                ];

                // Add the CSRF token security requirement
                $swaggerJson['security'] = [
                    ['csrf_token' => []],
                ];
            }

            $response->setContent($swaggerJson);
        }


        return $response;
    }

}