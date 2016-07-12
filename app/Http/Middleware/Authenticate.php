<?php

namespace Dashboard\Http\Middleware;

use Firebase\JWT\ExpiredException;
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
        $role = $this->arguments(func_get_args());

        if ($request->hasHeader('Authorization')) {
            return $this->Authorization(explode(' ', $request->header('Authorization'))[1],$request,$next,$role);
        } elseif ($request->has('Authorization')) {
            return $this->Authorization($request->input('Authorization'),$request,$next,$role);
        } else {
            return response()->json(['message' => 'Por favor verifique que la solicitud tenga un campo de autorización valido'], 401);
        }
    }

    private function Authorization($token,$request,Closure $next,$role){
        try{
            $payload = (array) JWT::decode($token, Config::get('app.jwt_token'), array('HS256'));

            if($payload == null)
                return response()->json(['message' => 'El Token Alterado'],401);

            /*
            * Restringir acceso por nivel de rol
            */
            if(!in_array($payload['role'],$role) && $role[0] != 'all')
                return response()->json(['message' => 'Acceso no autorizado'],401);

            $request['user'] = $payload;
            return $next($request);

        }catch( DomainException $e){
            return response()->json(['message' => 'El formato de autorizacion no es valido'], 401);
        }catch( ErrorException $e){
            return response()->json(['message' => 'El formato de la cabecera de autorizacion no es valido'], 401);
        }catch (ExpiredException $e){
            return response()->json(['message' => 'El tiempo de autorizacion Expiro'],401);
        }catch (\InvalidArgumentException $e){
            return response()->json(['message' => 'Argumento invalido'],401);
        }catch (\UnexpectedValueException $e){
            return response()->json(['message' => 'Valor inesperado', "error" => $e->getMessage()],401);
        }
    }

    private function arguments($args = []){
        $roles = array();
        foreach ($args as $key => $arg){
            if($key>1)
                $roles[] = $arg;
        }
        return $roles;
    }
}
