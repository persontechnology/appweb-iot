<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user=Auth::user();
        if($user && !$user->tenant_id){
            return redirect()->route('no-tiene-inquilino');
        }
        
        if($user && !$user->is_active){
            return redirect()->route('cuenta-inactiva');
        }


        return $next($request);
    }
}
