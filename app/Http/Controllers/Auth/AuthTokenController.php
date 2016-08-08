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
    public function __construct()
    {
        $this->middleware('auth:all', ['only' => 'dashboard']);
    }

    /**
     * Generate JSON Web Token.
     * If message is "token expired" remove token in database, automatic login generate new token
     */
    protected function createToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'role'=> $user->role->abrev,
            'iat' => time(),
            'exp' => time() + (63072000)
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

        if (Hash::check($password, $user->password) && $user->status == 1) {
            if(!$user->token){
                unset($user->password);
                $token = $this->createToken($user);
                $user->token = $token;
                $user->save();
            } else {
                $token = $user->token;
            }

            $rol = $user->role->name;
            $full_name = $user->last_name.', '.$user->first_name;
            return response()->json([
                                    'token' => $token,
                                    'role'=> $rol,
                                    'name' => $full_name
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

    /**
     *    Registro para usuarios tipo USC
     *
     *    @param  Illuminate\Http\Request  $request
     *    @return  Illuminate\Http\Response
     */
    public function signup_usc(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required|min:7',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => "Datos de registro no validos."], 422);
        }

        $validatorMail = Validator::make($request->all(), [
            'email' => 'unique:users,email',
        ]);

        if ($validatorMail->fails()) {
            return response()->json(['message' => "El correo ya se encuentra registrado."], 422);
        }


        $user = new User;
        $user->first_name = ucwords(request('name'));
        $user->last_name = "Asociado";
        $user->email = request('email');
        $user->phone = request('phone');
        $user->password = bcrypt(request('password'));
        $user->role_id = 7;
        $user->save();

        $user->status = 1;
        $user->save();

        return response()->json(["message" => "Registro completado puede ingresar a publicar"]);
    }

    public function dashboard(Request $request){
        
        $token = $request->input('Authorization');
        $user = User::where('token','=', $token)->first();
        
        try{
            if($user!= null) {

                if ($user->role->abrev == 'GOD') {
                    return view('auxGod');
                } else if ($user->role->abrev == 'ADM') {
                    return view('auxAdministrator');
                } else if ($user->role->abrev == 'VEN') {
                    return view('vendedor');
                } else if ($user->role->abrev == 'JVE') {
                    return view('auxCoordinador');
                } else if ($user->role->abrev == 'EMP') {
                    return view('empleado');
                } else if ($user->role->abrev == 'PUB') {
                    return view('publicidad');
                } else if ($user->role->abrev == 'PRO') {
                    return view('proveedor');
                } else if ($user->role->abrev == 'USC') {
                    return view('userCustomer');
                } else if ($user->role->abrev == 'STO') {
                    return view('auxStore');
                }
            }else{
                return view('logout');
            }
        }catch(\Exception $e){
            return "Error en login";
        }
        
    }

    public function dashboard_associated(Request $request){
        
        try{
            return view('associated');
        }catch(\Exception $e){
            return "Error en login";
        }
        
    }

    public function getDashboard(){
        return redirect('/');
    }
}
