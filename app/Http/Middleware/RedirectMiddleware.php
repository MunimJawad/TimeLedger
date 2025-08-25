<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$guards): Response
    {
      
             // Check if the user is authenticated (default guard 'web')
        if (Auth::check()) {
            return redirect()->route('profile'); // Use route name for flexibility
        }

      
        return $next($request);
    }
}
