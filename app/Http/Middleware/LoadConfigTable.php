<?php

namespace App\Http\Middleware;

use Closure;
use App\Configuration;

class LoadConfigTable
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
        app()->instance('reception_id',Configuration::getReceptionId());


        return $next($request);
    }
}
