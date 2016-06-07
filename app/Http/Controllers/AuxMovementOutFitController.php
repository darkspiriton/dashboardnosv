<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\MovementOutFit;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class AuxMovementOutFitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }
}
