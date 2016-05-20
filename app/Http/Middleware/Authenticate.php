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
    public function handle($request, Closure $next, $role = null)
    {
        if ($request->header('Authorization')) {
            return $this->Authorization(explode(' ', $request->header('Authorization'))[1],$request,$next,$role);
        } elseif ($request->input('Authorization')) {
            return $this->Authorization($request->input('Authorization'),$request,$next,$role);
        } else {
            return response()->json(['message' => 'Por favor verifique que la solicitud tentga un campo de autorizacion valido'], 401);
        }
    }

    private function Authorization($authorization,$request,Closure $next,$role){
        try{
            $token = $authorization;

            $payload = (array) JWT::decode($token, Config::get('app.jwt_token'), array('HS256'));

            if ($payload['exp'] < time())
                return response()->json(['message' => 'El tiempo de autorizacion Expiro'],404);

            if($payload == null)
                return response()->json(['message' => 'El Token Alterado'],401);
            /*
            * Restringir acceso por nivel de rol
            */
//            if($payload['role'] != $role)
//                return response()->json(['message' => 'Acceso no autorizado'],401);

            $request['user'] = $payload;
            return $next($request);
        }catch( DomainException $e){
            return response()->json(['message' => 'El formato de autorizacion no es valido'], 401);
        }catch( ErrorException $e){
            return response()->json(['message' => 'El formato de la cabecera de autorizacion no es valido'], 401);
        }
    }
}
