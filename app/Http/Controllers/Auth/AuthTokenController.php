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
        return JWT::encode($payload, Config::get('app.jwt_token'));
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
            $token = $this->createToken($user);

            unset($user->password);
            $user->token = $token;
            $user->save();

            $rol = $user->role->name;
            $full_name = $user->last_name.', '.$user->first_name;
            return response()->json([
                                    'token' => $token,
                                    'role'=> $rol,
                                    'name' => $full_name,
                                    'routes' => Config::get('angularJSRoutes.'.$rol)
                                    ]);
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

    public function dashboard(Request $request){
        $token = $request->input('Authorization');
        $user = User::where('token','=', $token)->first();
        if ($user->role->abrev == 'GOD' ){
            return view('auxGod');
        } else if ($user->role->abrev == 'ADM') {
            return view('auxAdministrator');
        } else if ($user->role->abrev == 'VEN') {
            return view('vendedor');
        } else if ($user->role->abrev == 'JVE') {
            return view('auxCoordinador');
        }
    }
}
