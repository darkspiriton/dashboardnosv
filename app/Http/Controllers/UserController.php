<?php

namespace Dashboard\Http\Controllers;

use Dashboard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Dashboard\Http\Requests;

class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:GOD');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users= DB::table('users')
            ->join('roles','users.role_id','=','roles.id')
            ->orderBy('roles.name','asc')
            ->select('users.id',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),'roles.name AS rol','users.status')
            ->get();
        return response()->json(['users' => $users],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'first_name'      => 'required',
            'last_name'      => 'required',
            'email'     => 'required|email',
            'phone'      => 'required',
            'address'      => 'required',
            'sex'      => 'required',
            'role_id'      => 'required',
            'password'      => 'required',
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un usuario'],401);
            }

            // Si el validador pasa, almacenamos el comentario
            $userAux=DB::table('users')->where('email',$request->input('email'))->get();

            if(count($userAux) == 0 ){
                $user = new User();
                $user->first_name= $request->input('first_name');
                $user->last_name= $request->input('last_name');
                $user->email= $request->input('email');
                $user->phone= $request->input('phone');
                $user->address= $request->input('address');
                $user->sex= $request->input('sex');
                $user->role_id= $request->input('role_id');
                //$user->user= $request->input('user');
                $user->password= bcrypt($request->input('password'));
                $user->save();
                
                return response()->json(['message' => 'Se usuario se agrego correctamente'],200);
            }

                return response()->json(['message' => 'El usuario ya esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar usuario'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $user = User::find($id);
            $user->role;
            if ($user !== null) {
                return response()->json([
                        'message' => 'Mostrar detaller de user',
                        'user' => $user,
                    ],200);
            }
            return response()->json(['message' => 'No existe ese usuario'], 404);

        }catch (\ErrorException $e){
            return response()->json(['message' => 'Ocurrio un error'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $user = User::find($id);
            if ($user !== null) {
                $user->first_name= $request->input('first_name');
                $user->last_name= $request->input('last_name');
                $user->email= $request->input('email');
                $user->phone= $request->input('phone');
                $user->address= $request->input('address');
                $user->sex= $request->input('sex');
                $user->role_id= $request->input('role_id');
                $user->status= $request->input('status');
                //$user->user= $request->input('user');
                if($request->input('password') !== null ){
                    $user->password= bcrypt($request->input('password'));
                }

                $user->save();
                return response()->json(['message' => 'Se actualizo correctamente'],200);
            }
            return \Response::json(['message' => 'No existe ese usuario'], 404);

        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $user = User::find($id);
            if ($user !==null){
                if ($user->status == 1){
                    $user->status=0;
                    $user->save();
                    return response()->json(['message' => 'Se desactivo correctamente el usuario'],200);
                }elseIf($user->status == 0){
                    $user->status=1;
                    $user->save();
                    return response()->json(['message' => 'Se activo correctamente el usuario'],200);
                }
            }
            return \Response::json(['message' => 'No existe ese usuario'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }

}
