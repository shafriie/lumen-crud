<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use App\Helpers\WaferBaseHelp;
use App\User;

class TokenMiddleware
{
    public function __construct()
    {
        $this->globHelper = new WaferBaseHelp();
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
        # get bearer token
        $token = $request->bearerToken();

        # check token, if empty error
        if (!$token) {
            return response()->json([
                'success' => false, 'message' => 'Please input authorization Token!'
            ], 401);
        }

        # validate token jwt
        try {
            $credentials = JWT::decode($token, env('APP_KEY'), ['HS256']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 'message' => $e->getMessage()
            ], 400);
        }

        // dd($credentials);

        # get id decrypt
        $id = $this->globHelper->decryptGlobal($credentials->sub, 'jij!HeReasfwn13');
        
        # set config global 
        config(['jwtUser' => User::find($id)]);

        return $next($request);
    }
}
