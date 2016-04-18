<?php

namespace Dashboard\Http\Middleware;

use Firebase\JWT\JWT;
use Config;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use DomainException;
use ErrorException;

class Authenticate {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
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
        if ($request->header('Authorization'))
        {
            try{
                $token = explode(' ', $request->header('Authorization'))[1];

                $payload = (array) JWT::decode($token, Config::get('app.jwt_token'), array('HS256'));
                if ($payload['exp'] < time())
                {
                    return response()->json(['message' => 'El Token Expiro'],404);
                }
                $request['user'] = $payload;
                return $next($request);
            }catch( DomainException $e){
                return response()->json(['message' => 'El formato de la Key no es Valido'], 401);

            }catch( ErrorException $e){
                return response()->json(['message' => 'El formato del Header de la Key no es Valido'], 401);
            }

        } elseif ($request->input('Authorization'))
        {
            try{

                $token = $request->input('Authorization');

                $payload = (array) JWT::decode($token, Config::get('app.jwt_token'), array('HS256'));

                if ($payload['exp'] < time())
                {
                    return response()->json(['message' => 'El Token Expiro']);
                }

                $request['user'] = $payload;

                return $next($request);
            }catch( DomainException $e){
                return response()->json(['message' => 'El formato de la Key no es Valido'], 401);

            }catch( ErrorException $e){
                return response()->json(['message' => 'El formato del Header de la Key no es Valido'], 401);
            }

        }
        else
        {
            return response()->json(['message' => 'Por favor verificque que el request posee un campo de token valido'], 401);
        }
    }
}
