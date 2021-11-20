<?php

namespace App\Http\Middleware;
use Closure;
use Auth;

class AccessCompras
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->funcao == 1 || Auth::user()->funcao == 3){
            return $next($request);
        }else{
            return redirect('/');
        }
    }
}
