<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JsonMiddleware
{
    public function handle($request, Closure $next)
    {
        $header = $request->header('Accept');
        if ($header != 'application/json') {
            return response(['messagem' => 'Accept tipo inválido'], 406);
        }

        if (!$request->isMethod('post')) return $next($request);

        $header = $request->header('Content-type');
        if (!Str::contains($header, 'application/json')) {
            return response(['mensagem' => 'Content-type tipo inválido'], 406);
        }

        return $next($request);
    }
}
