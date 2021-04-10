<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class AdminMiddleware extends Middleware
{

    public function handle($request, Closure $next)
    {
		//Comprobamos que el usuario tenga id_rol == 1 el cual corresponde con ser administrador
		if (auth()->check() && auth()->user()->id_role == 1)
        	return $next($request);
    	return redirect('/');
	}
}