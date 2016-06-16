<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Dashboard\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function validate(Request $request){
        try{
            $token = explode(' ', $request->header('Authorization'))[1];

            $payload = (array) JWT::decode($token, \Config::get('app.jwt_token'), array('HS256'));

            if($payload == null)
                return response()->json(['message' => 'El Token Alterado'],401);

            return response()->json(['message' => 'Autorizacion valida'],200);
        }catch( \DomainException $e){
            return response()->json(['message' => 'El formato de autorizacion no es valido'], 401);
        }catch( \ErrorException $e){
            return response()->json(['message' => 'El formato de la cabecera de autorizacion no es valido'], 401);
        }catch (ExpiredException $e){
            User::update(['token' => null]);
            return response()->json(['message' => 'El tiempo de autorizacion Expiro'],401);
        }catch (\InvalidArgumentException $e){
            return response()->json(['message' => 'Argumento invalido'],401);
        }catch (\UnexpectedValueException $e){
            return response()->json(['message' => 'Valor inesperado'],401);
        }
    }
}
