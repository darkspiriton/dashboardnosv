<?php

namespace Dashboard\Http\Controllers;

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
    public function index()
    {

        //Se va a pasar datos del los producto, attributos y su cantidad
        try {

            $products = DB::table('auxproducts AS p')
                ->select(DB::raw('count(*) as cant'),'p.name','c.name AS color','s.name AS size','p.id','p.cod')
                ->join('colors AS c','c.id','=','p.color_id')
                ->join('sizes AS s','s.id','=','p.size_id')
                ->join('auxmovements AS m','m.product_id','=','p.id')
                ->groupby('p.name')
                ->orderby('cant','asc')->get();


            if($products != null){
                return response()->json(['products' => $products],200);
            } else {
                return response()->json(['message' => 'No hay productos en existencia'],401);
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

    public function search($string){
        $product = Product::where('name','LIKE','%'.$string.'%')->orWhere('phone','LIKE','%'.$string.'%')->get();
        return response()->json( ['customers' => $product] ,200);
    }

}
