<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Order\Order;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json(['interests' => $orders],200);
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
            'customer_id'      => 'required',
            'status_id'      => 'required',
            'observation'      => 'required',
            //falta validar los atributos
        ];

        try {

            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear una orden de pedido'],401);
            }

            $order = new Order();
            $order->customer_id= $request->input('customer_id');
            $order->user_id= $user['sub'];
            $order->status_id= $request->input('status_id');
            $order->interest_id= $request->input('interest_id');
            $order->save();

            $details = $request->input('details');
            $orderAux = DB::table('orders')
                ->where('customer_id','=',$request->input('customer_id'))
                ->orderBy('created_at','desc')
                ->first();

           foreach ($details as $detail){
               $orderDetail= New Detail();
               $orderDetail->order_id = $orderAux->id;
               $orderDetail->product_id = $detail->product_id;
               $orderDetail->quantity = $detail->quantity ;
               $orderDetail->price =  $detail->price;
               $orderDetail->details()->save($orderDetail);
           }

            //Falta Agregar atributos de productos
            return response()->json(['message' => 'El orden de pedido se agrego correctamente'],200);

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
            $order = Order::find($id);
            if ($order !== null) {
                $order->details;
                $order->customer;
                //$order->shipments;
                $order->calls;
                $order->status;
                $order->user;
                return response()->json([
                    'message' => 'Mostrar detalles de orden de pedido',
                    'order'=> $order,

                    //'attributes' => $product->attributes,
                ],200);
            }
            return \Response::json(['message' => 'No existe la orden de pedido'], 404);

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
                $interest->customer_id= $request->input('customer_id');
                $interest->user_id= $user['sub'];
                $interest->status_id= $request->input('status_id');
                $interest->interest_id= $request->input('interest_id');
                $interest->save();

                //falta vincular el detalle

                return response()->json(['message' => 'Se actualizo correctamente la orden de pedido'],200);
            }
            return \Response::json(['message' => 'No existe esa orden de pedido'], 404);
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
    public function destroy(Request $request,$id)
    {
        try{
            $order = Order::find($id);
            if($order != null) {
                $order->status_id=$request->input('status_id');
                $order->save();
                return response()->json(['message' => 'Se desactivo correctamente el registro de interes'],200);
            }
                return \Response::json(['message' => 'No existe el registro de interes'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }
}
