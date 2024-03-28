<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Agent;
use App\Models\Setting;

class IsAdminMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Agent::isAdmin()) {
            return $next($request);
        }
        return redirect()->route('tickets.index')->with('warning', trans('lang.you-are-not-permitted-to-access'));
    }
}
