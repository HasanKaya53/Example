<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Libraries\Response as ResponseService;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $expectedUsername = 'admin';
        $expectedPassword = 'admin';

        // İstekteki kullanıcı adı ve şifreyi al
        $username = $request->header('PHP_AUTH_USER');
        $password = $request->header('PHP_AUTH_PW');

        // Kullanıcı adı ve şifreyi kontrol et
        if ($username !== $expectedUsername || $password !== $expectedPassword) {
            return ResponseService::responseJson(401, [], 'Unauthorized');
        }

        return $next($request);
    }
}
