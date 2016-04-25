<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Scope\Detail;
use Dashboard\Models\Scope\Scope;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class ScopeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scopes = Scope::all();
        return response()->json(['scopes' => $scopes],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=$request->input('user');
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }

        // Creamos las reglas de validación
        $rules = [
            'channel_id'      => 'required',
            'type_id'      => 'required',
            'observation'     => 'required',
            'name'     => 'required',
            //falta validar los atributos
        ];

        try {

            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un cliente'],401);
            }
            $name = $request->input('name');
            $scope = new Scope();
            $scope->user_id= $user['sub'];
            $scope->channel_id= $request->input('channel_id');
            $scope->type_id= $request->input('type_id');
            $scope->observation= $request->input('observation');
            $scope->name= strtolower($name);
            $scope->save();

            $detalles = $request->input('scopes');

            foreach ($detalles as $detalle){
                $detail= New Detail();
                $detail->scope_id=$scope->id;
                $detail->product_id=$detalle->product_id;
                $scope->details()->save($detail);
            }

            //Falta Agregar atributos de productos
            return response()->json(['message' => 'El cliente se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar cliente'], 500);
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
            $scope = Scope::find($id);
            if ($scope !== null) {

                $scope->details;

                return response()->json([
                    'message' => 'Mostrar detalles de producto',
                    'scope'=> $scope,

                    //'attributes' => $product->attributes,
                ],200);
            }
            return \Response::json(['message' => 'No existe ese producto'], 404);

        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
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
        $user=$request->input('user');

        try{
            $scope = Scope::find($id);
            if ($scope !== null) {
                $name = $request->input('name');
                $scope->user_id= $user['sub'];
                $scope->channel_id= $request->input('channel_id');
                $scope->type_id= $request->input('type_id');
                $scope->observation= $request->input('observation');
                $scope->name= strtolower($name);
                $scope->save();

//                $product = DB::table('products')->where('name','=',$request->input('name'))->get();
//                $cant = $request->input('cant');
//                $attributes=$request->input('attributes');
//                $this->addKardex($product->id,$cant,$attributes);

                return response()->json(['message' => 'Se actualizo correctamente'],200);
            }
            return \Response::json(['message' => 'No existe ese usuario'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }
}
