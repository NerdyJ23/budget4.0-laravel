<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Clients\Users\UserClient;

use App\Exceptions\Http\UnauthorizedException;

class AuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if ($request->cookie('token') == null || UserClient::getByToken(token: $request->cookie('token')) == null) {
			if ($request->is('api/*')) {
				throw new UnauthorizedException('User not logged in');
			}
			return redirect()->route('home');
		}
        return $next($request);
    }
}
