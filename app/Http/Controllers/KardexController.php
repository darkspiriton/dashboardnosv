<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Kardex\Group_Attribute;
use Dashboard\Models\Kardex\Kardex;
use Illuminate\Http\Request;
use Dashboard\Models\Product\Product;

use Dashboard\Http\Requests;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kardexs= Product::all();
        foreach ($kardexs as $kardex){
            $kardex->kardexs;
        }
        return response()->json(['products' => $kardexs],200);
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
            $kardex = Kardex::find($id);

            if ($kardex !== null) {
               $kardex->group_kardex->attributes;
                //$group = Group_Attribute::find($idG);
                //$group->group_kardex;
                //$group = Group_Attribute::find($idAux);
                
                return response()->json([
                    'message' => 'Mostrar detalles de producto',
                    'kardex'=> $kardex,
                    //'attributes' => $product->attributes,
                ],200);
            }
            return \Response::json(['message' => 'No existe ese producto'], 404);

        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
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
