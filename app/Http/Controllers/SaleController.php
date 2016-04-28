<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Sale\Sale;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales=Sale::all();
        return response()->json(['sales' => $sales],200);
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
            'shipment_id'      => 'required',
            'total'      => 'required',
            'received'      => 'required',
            'change'      => 'required',
            'observation'      => 'required',
            'date'      => 'required',
            //falta validar los atributos
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear una venta'],401);
            }

            $sale = new Sale();
            $sale->shipment_id = $request->input('shipment_id');
            $sale->total = $request->input('total');
            $sale->received = $request->input('received');
            $sale->change = $request->input('change');
            $sale->observation = $request->input('observation');
            $sale->date = $request->input('date');
            $sale->save();

            return response()->json(['message' => 'La venta se agrego correctamente'],200);

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
            $sale = Sale::find($id);
            if ($sale !== null) {
                $sale->commissions;
                return response()->json([
                    'message' => 'Mostrar detalles de la venta',
                    'sale'=> $sale
                ],200);
            }
            return \Response::json(['message' => 'No existe la venta'], 404);
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
            $sale = Sale::find($id);
            if ($sale !== null) {
                $sale->shipment_id = $request->input('shipment_id');
                $sale->total = $request->input('total');
                $sale->received = $request->input('received');
                $sale->change = $request->input('change');
                $sale->observation = $request->input('observation');
                $sale->date = $request->input('date');
                $sale->save();

                //Falta calculo de comisiones

                return response()->json(['message' => 'Se actualizo correctamente la venta'],200);
            }
            return \Response::json(['message' => 'No existe esa venta'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }
}
