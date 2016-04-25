<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Interest\Detail;
use Dashboard\Models\Interest\Interest;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interests = Interest::all();
        return response()->json(['interests' => $interests],200);
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
            'status_id'      => 'required',
            'channel_id'      => 'required',
            'customer_id'      => 'required',
            'observation'      => 'required',
            //falta validar los atributos
        ];

        try {

            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un registro de interes'],401);
            }

            $interest = new Interest();
            $interest->user_id= $user['sub'];
            $interest->channel_id= $request->input('channel_id');
            $interest->status_id= $request->input('status_id');
            $interest->customer_id= $request->input('customer_id');
            $interest->observation= $request->input('observation');
            $interest->save();

            $detalles = $request->input('interests');
            $interestAux = DB::table('interests')
                        ->where('customer_id','=',$request->input('customer_id'))
                        ->orderBy('created_at','desc')
                        ->get();

            foreach ($detalles as $detalle){
                $detail= New Detail();
                $detail->interest_id=$interestAux->id;
                $detail->product_id=$detalle->product_id;
                $interestAux->details()->save($detail);
            }

            //Falta Agregar atributos de productos
            return response()->json(['message' => 'El registro de interes se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar registro de interes'], 500);
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
            $interest = Interest::find($id);
            if ($interest !== null) {

                $interest->details;
                $interest->customer;
                $interest->status;
                $interest->channel;
                $interest->user;

                return response()->json([
                    'message' => 'Mostrar detalles de registro de interes',
                    'interest'=> $interest,

                    //'attributes' => $product->attributes,
                ],200);
            }
            return \Response::json(['message' => 'No existe ese registro de interes'], 404);

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
            $interest = Interest::find($id);
            if ($interest !== null) {
                $interest->user_id= $user['sub'];
                $interest->channel_id= $request->input('channel_id');
                $interest->status_id= $request->input('status_id');
                $interest->customer_id= $request->input('customer_id');
                $interest->observation= $request->input('observation');
                $interest->save();

                //falta vincular el detalle

                return response()->json(['message' => 'Se actualizo correctamente el registro de interes'],200);
            }
            return \Response::json(['message' => 'No existe ese registro de interes'], 404);
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
            $interest = Interest::find($id);
            if ($interest->status == 1){
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
}
