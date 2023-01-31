<?php

namespace Aqayepardakht\Logger\Http\Middleware;

use Aqayepardakht\Logger\Telescope;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return Telescope::check($request) ? $next($request) : abort(403);
    }
}
