<?php

namespace Dashboard\Http\Controllers\Auth;


use Hash;
use Config;
use Validator;
use Firebase\JWT\JWT;

use Dashboard\User;
use Dashboard\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AuthTokenController extends Controller
{
    /**
     * Generate JSON Web Token.
     */
    protected function createToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, Config::get('app.token_secret'));
    }

    /**
     * Log in with Email and Password.
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email','=', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'El usuario y/o contraseña no son validos'], 401);
        }

        if (Hash::check($password, $user->password)) {
            unset($user->password);

            return response()->json(['token' => $this->createToken($user)]);
        } else {
            return response()->json(['message' => 'Wrong email and/or password'], 401);
        }
    }
}
