<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Dashboard\Models\Shipment\Shipment;
use Dashboard\Events\ShipmentWasRegistered;
use Illuminate\Http\Request;
use Dashboard\Events\Event;

use Dashboard\Http\Requests;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shipments = Shipment::all();
        return response()->json(['interests' => $shipments],200);
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
            'order_id'      => 'required',
            'address_id'      => 'required',
            'status_id'      => 'required',
            'type_ship_id'      => 'required',
            'date'      => 'required',
            'cost'      => 'required',
            //falta validar los atributos
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear una orden de envio'],401);
            }

            $shipment = new Shipment();
            $shipment->order_id= $request->input('order_id');
            $shipment->address_id= $request->input('address_id');
            $shipment->status_id= $request->input('status_id');
            $shipment->type_ship_id= $request->input('type_ship_id');
            $shipment->date= $request->input('date');
            $shipment->cost= $request->input('cost');
            $shipment->save();


            //Generar Movimientos de Kardex
            Event::fire(new ShipmentWasRegistered($shipment));
            return response()->json(['message' => 'El orden de envio se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar la orden de pedido'], 500);
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
            $shipment = Shipment::find($id);
            if ($shipment !== null) {
                $shipment->status;
                $shipment->type;
                $shipment->order;
                $shipment->address;
//                $shipment->routes;
//                $shipment->sales;
//                $shipment->tracings;
//                $shipment->movements;
                return response()->json([
                    'message' => 'Mostrar detalles de orden de envio',
                    'shipment'=> $shipment
                ],200);
            }
            return \Response::json(['message' => 'No existe la orden de envio'], 404);

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
        try{
            $shipment = Shipment::find($id);
            if ($shipment !== null) {
                $shipment->order_id= $request->input('order_id');
                $shipment->address_id= $request->input('address_id');
                $shipment->status_id= $request->input('status_id');
                $shipment->type_ship_id= $request->input('type_ship_id');
                $shipment->date= $request->input('date');
                $shipment->cost= $request->input('cost');
                $shipment->save();

                //Faltar vincular la actualizacion del detalle de movimiento del kardex

                return response()->json(['message' => 'Se actualizo correctamente la registro de envio'],200);
            }
            return \Response::json(['message' => 'No existe el registro de envio'], 404);
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
    public function destroy(Request $request, $id)
    {
        //Esto no elimina la orden de envio solo actualiza su estado
        try{
            $shipment = Shipment::find($id);
            if($shipment != null) {
                $shipment->status_id=$request->input('status_id');
                $shipment->save();
                return response()->json(['message' => 'Se actualizo correctamente el registro de envio'],200);
            }
            return \Response::json(['message' => 'No existe el registro de envio'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }
}
