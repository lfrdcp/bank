<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class verificarPago
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if ($request->user()->verificarPago()) {
                return $next($request);
            } else {
                return redirect(route('debePagar'));
            }
        } else {
            return redirect(route('login'));
        }
    }
}
