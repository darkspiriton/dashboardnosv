<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Movement;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Dashboard\Http\Requests;

class AuxMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Creamos las reglas de validación
        $rules = [
            'name'          => 'required',
            'color_id'         => 'required',
            'size_id'     => 'required',
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un producto'],401);
            }

            $product = DB::table('auxproducts AS p')
                                ->select(DB::raw('count(*) as cant'),'p.name','p.id')
                                ->join('auxmovements AS m','m.product_id','=','p.id')
                                ->where('name','LIKE','%'.$request->input('name').'%')
                                ->where('color_id','=',$request->input('color_id'))
                                ->where('size_id','=',$request->input('size_id'))
                                ->groupby('p.name')
                                ->orderby('cant','asc')->get();
            

            if($product != null){
                return response()->json(['product' => $product],200);
            } else {
                return response()->json(['message' => 'No existe producto con esas caracteristicas'],401);
            }

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Creamos las reglas de validación
        $rules = [
            'product_id'    => 'required',
            'date_shipment' => 'required',
            'situation'     => 'required',
            'status'        => 'required'
        ];

        //Se va a pasar datos del movimiento
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un movimiento'],401);
            }

            $product=Product::find($request->input('product_id'));
            $move=$product->movements->first();

            if($move->status != 'vendido'){
                $movement = new Movement();
                $movement->product_id=$request->input('product_id');
                $movement->date_shipment=$request->input('date_shipment');
                $movement->status=$request->input('status');
                if($request->input('situation') == 'retorno'){
                    $movement->situation=$request->input('situation');
                }
                $movement->save();
            }
            return response()->json(['message' => 'El producto se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
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
        //
    }

    public function sale(Request $request){
        // Creamos las reglas de validación
        $rules = [
            'product_id'    => 'required',
            'date_shipment' => 'required',
            'situation'     => 'required',
            'status'        => 'required'
        ];

        //Se va a pasar datos del movimiento
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un movimiento'],401);
            }

            $product=Product::find($request->input('product_id'));
            $move=$product->movements->first();

            if($move->status != 'vendido'){
                $movement = new Movement();
                $movement->product_id=$request->input('product_id');
                $movement->date_shipment=$request->input('date_shipment');
                $movement->status=$request->input('status');
                $movement->save();
                
                $product->status = 0;
                $product->save();
            }
            return response()->json(['message' => 'La venta se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar una venta'], 500);
        }
    }

    public function movementPending(Request $request){
        $product = DB::table('auxproducts AS p')
            ->select(DB::raw('count(*) as cant'),'p.name','p.id')
            ->join('auxmovements AS m','m.product_id','=','p.id')
            ->where('name','LIKE','%'.$request->input('name').'%')
            ->where('color_id','=',$request->input('color_id'))
            ->where('size_id','=',$request->input('size_id'))
            ->groupby('p.name')
            ->orderby('cant','asc')->get();
    }

}
