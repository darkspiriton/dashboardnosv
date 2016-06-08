<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Settlement;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class liquidationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Settlement::with(['product' => function($query){
            return $query->with('provider','color','size');
        }])->get();

        return response()->json(['products' => $products],200);
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
            'id'    =>  'required|integer|exists:auxproducts,id|unique:settlements,product_id',
            'price' =>  'required|numeric'
        ];

        if(\Validator::make($request->all(), $rules)->fails())
            return response()->json(['message' => 'Parametros recibidos no validos'],401);

        $liquidation = new Settlement();
        $liquidation->product_id = $request->input('id');
        $liquidation->price = $request->input('price');
        $liquidation->save();

        return response()->json(['message' => 'Se genero la liquidacion correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $liquidation = Settlement::with('product')->find($id);

        if($liquidation == null)
            return response()->json(['message' => 'El producto en liquidacion no existe'],404);

        if($liquidation->product->status != 1)
            return response()->json(['message' => 'El producto esta en salida o vendido no se puede eliminar'],401);

        $liquidation->delete();

        return response()->json(['message' => 'El retiro el producto del area de liquidacion'],200);
    }
}
