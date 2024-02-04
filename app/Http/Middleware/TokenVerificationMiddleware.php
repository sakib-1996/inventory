<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWTToken::ReadToken($token);
        if ($result == "unauthorized") {
            return ResponseHelper::Out('unauthorized', null, 401);
        } else {
            $request->headers->set('email', $result->userEmail);
            $request->headers->set('id', $result->userID);
            $request->headers->set('role', $result->userRole);
            return $next($request);
        }
    }
}
