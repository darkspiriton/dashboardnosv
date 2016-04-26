<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Kardex\Kardex;
use Illuminate\Http\Request;
use Dashboard\Models\Product\Product;
use DB;
use Dashboard\Http\Requests;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->input('id');
        $kardexs= Product::find($id);
        $kardexs->kardexs;
        return response()->json(['products' => $kardexs],200);
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
                $productAux= DB::table('products AS p')
                        ->select('k.id','ta.name','att.valor')
                        ->join('kardexs AS k','k.product_id','=','p.id')
                        ->join('groups_attributes AS g','g.id','=','k.group_attribute_id')
                        ->join('attributes_kardexs AS attk','attk.group_attribute_id','=','g.id')
                        ->join('attributes AS att','att.id','=','attk.attribute_id')
                        ->join('types_attributes AS ta','ta.id','=','att.type_id')
                        ->where('k.id','=',$id)
                        //->distinct('kardexs.id','types_attributes.name','attributes.valor')
                        ->get();
                
                return response()->json([
                    'message' => 'Mostrar detalles de producto',
                    'kardex'=> $productAux,
                    //'attributes' => $product->attributes,
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
