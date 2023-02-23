<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectOnMethodError
{
    /**
     * Handle an incoming request and throw the user back to root, if
     * the error code is between 400-500.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pass the request to the next middleware in the stack.
        $response = $next($request);
        // Check if the request method is POST and an error occurred.
        if ( 500 > $response->getStatusCode() && $response->getStatusCode() >= 400) {
            // Redirect to the most possible previous page which doesn't give error code.
            return back();
        }
        return $response;
    }
}
