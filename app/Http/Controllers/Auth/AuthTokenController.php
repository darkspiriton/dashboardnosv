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

            return response()->json(['token' => $this->createToken($user), 'role' => 'VEN' , 'routes' => Config::get('angularJSRoutes.VEN')]);
        } else {
            return response()->json(['message' => 'El usuario y/o contraseña no son validos'], 401);
        }
    }

    /**
     * Create Email and Password Account.
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json(['token' => $this->createToken($user)]);
    }
}
