<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommingSoonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // dd($request->url())
        // if (auth()->check()) {
        //     if (!in_array(auth()->user()->phone_number, config('app.admins'))) {
        //         // dd(auth()->user());
        //         abort(403, 'Comming soon, stay tuned');
        //     }
        // } else {
        //     abort(403, 'Comming soon, stay tuned');
        // }

        return $next($request);
    }
}
