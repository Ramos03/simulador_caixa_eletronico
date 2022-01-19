<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class apiProtectedRoute extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['mensagem' => 'Token inválido'], 400);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['mensagem' => 'O token expirou'], 400);
            } else if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json(['mensagem' => 'Token não autorizado'], 401);
            } else {
                return response()->json(['mensagem' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}
