<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\MovementOutFit;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class AuxMovementOutFitController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:GOD,ADM,JVE');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outfits = MovementOutFit::with('outfit')->where('status','=','salida')->where('respond','=',0)->get();
        return \Response::json(['outfits' => $outfits], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'outfit_id'   =>  'required|integer|exists:outfits,id',
            'date'        =>  'required|date',
            'products'    =>  'required|array',
            'products.*.id'    =>  'required|integer|exists:auxproducts,id'
        ];

        if(\Validator::make($request->all(),$rules)->fails())
            return response()->json(['message' => 'Parametros recibidos no validos'],401);

        $outfit_movement = new MovementOutFit();

        $outfit_movement->outfit_id = $request->input('outfit_id');
        $outfit_movement->date_shipment = $request->input('date');
        $outfit_movement->status = 'salida';
        $outfit_movement->save();

        $products = array();
        foreach ($request->input('products') as $i => $product){
            $mProduct = Product::find($product['id']);
            $mProduct->status = 0;
            $mProduct->save();
            $products[] = $mProduct;
        }

        $outfit_movement->products()->saveMany($products);

        return response()->json(['message' => 'Se genero salida de outfit'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movement = MovementOutFit::with('products')->find($id);

        if($movement == null)
            return response()->json(['message' => 'El movimiento no existe'],404);

        return response()->json(['movement' => $movement],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        if(\Validator::make($request->all(), ['case'  =>  'required|integer|in:1,2'])->fails())
            return response()->json(['message' => 'Parametros recibidos no validos'], 401);

        $movement = MovementOutFit::with('products')->find($id);

        if($movement == null)
            return response()->json(['message' => 'El movimiento no existe'], 404);

        if($movement->status == 'vendido' || $movement->products[0]->status == 2)
            return response()->json(['message' => 'Este movimiento se cuentra con estado "VENDIDO"'], 401);

        $message = '';

        if($request->input('case') == 1){
            // Replicando movimiento
            $newMovement = $movement->replicate();
            $newMovement->status = 'vendido';
            $newMovement->push();
            $newMovement->products()->saveMany($movement->products);

            // Replicando productos del movimiento
            Product::whereIn('id',$movement->products->lists('id'))->update(['status' => 2]);

            $message = 'Se genero la venta correctamente';
        }else if($request->input('case') == 2){
            // Replicando movimiento
            $newMovement = $movement->replicate();
            $newMovement->status = 'retornado';
            $newMovement->situation = $request->input('situation');
            $newMovement->push();
            $newMovement->products()->saveMany($movement->products);

            // Replicando productos del movimiento
            Product::whereIn('id',$movement->products->lists('id'))->update(['status' => 1]);

            $message = 'Se genero el retorno correctamente';
        }

        // Movimiento base atendido
        $movement->respond = 1;
        $movement->save();

        return response()->json(['message' => $message],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
