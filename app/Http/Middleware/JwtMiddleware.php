<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        echo "0";
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                var_dump("1");
                return response()->json(['status' => 'Token is Invalid'], 403);
            } else if ($e instanceof TokenInvalidException) {
                var_dump("2");
                return response()->json(['status' => 'Token is Expired'], 401);
            } else if ($e instanceof TokenInvalidException) {
                var_dump("3");
                return response()->json(['status' => 'Token is Blacklisted'], 400);
            } else {
                var_dump("4");
                return response()->json(['status' => 'Authorization Token not found'], 404);
            }
        }
        echo "5";

        return $next($request);
    }
}
