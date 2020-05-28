<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class asignarBase
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
        Config::set("database.connections.pgsql.host", 'localhost');
        Config::set("database.connections.pgsql.database", 'B4nC0');
        Config::set("database.connections.pgsql.username", 'B4nC0');
        Config::set("database.connections.pgsql.password", '');
        Schema::connection('pgsql')->getConnection()->reconnect();

        return $next($request);
    }
}
