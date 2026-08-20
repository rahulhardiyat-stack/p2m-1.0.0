<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class AdminLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('admin_locale', 'id');

        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'id';
        }

        // Store back in session
        session(['admin_locale' => $locale]);

        // Forcefully set locale before request is handled
        App::setLocale($locale);
        \Carbon\Carbon::setLocale($locale);

        return $next($request);
    }
}
