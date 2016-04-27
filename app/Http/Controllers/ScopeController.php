<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Scope\Detail;
use Dashboard\Models\Scope\Scope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'channel_id'   => 'required',
            'type_id'      => 'required',
            'observation'  => 'required',
            'name'         => 'required'
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

            //Falta validar la creacion de detalle de alcance 26-04-2016
            $details = $request->input('scopes');
            $scopeAux = DB::table('orders')
                ->where('customer_id','=',$request->input('customer_id'))
                ->orderBy('created_at','desc')
                ->first();

            //Recorrer el detalle de alcance y los agrega
            foreach ($details as $detail) {
                $scopeDetail = New Detail();
                $scopeDetail->scope_id = $scopeAux->id;
                $scopeDetail->product_id = $detail->product_id;
                $scopeAux->details()->save($scopeDetail);
            }

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

                //Faltar vincular la actualizacion del detalle del alcance
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Esto no elimina la orden de pedido solo actualiza su estado
        try{
            $scope = Scope::find($id);
            if ($scope->status == 1){
                $interest->status = 0 ;
                $interest->save();
                return response()->json(['message' => 'Se desactivo correctamente el registro de interes'],200);
            }elseif ($interest->status == 0){
                $interest->status = 1;
                $interest->save();
                return response()->json(['message' => 'Se activo correctamente el registro de interes'],200);
            }
            return \Response::json(['message' => 'No existe el registro de interes'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }

    public function types(){
        $types = DB::table('types_scope')->get();
        if(!is_null($types)){
            return response()->json([
                'message' => 'Mostrar todos los canales',
                'types'=> $types,
                //'attributes' => $product->attributes,
            ],200);
        } else{
            return response()->json([
                'message' => 'No se encuentran los tipos de alcanse'
            ],404);
        }
    }
}
