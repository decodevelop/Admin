<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Route;
use Auth;
use Closure;
//use Illuminate\Contracts\Auth\Guard;
class ControlPermisos
{
	
	
	public function __construct(Guard $auth){
		$this->auth = $auth;
	 }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	 
	 
    public function handle($request, Closure $next)
    {
		$user    = $this->auth->user();
        $roles   = $this->getRoles($request);
		
		//print_r($roles);

		if(Auth::user()->permisos!="bloqueado" || Auth::user()->permisos!="bloqueado"){
			
		}
        return $next($request);
    }
	
	 private function getRoles($request)
    {
        $roles = [];
        $route   = $request->route();
        $actions = $route->getAction();

        if (is_array($actions['role']))
        {
            return array_merge($roles, $actions['role']);
        }

        $roles[] = $actions['role'];

        return $roles;
    }
}
