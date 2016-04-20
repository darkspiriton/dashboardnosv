<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dashboard\Http\Requests;
use Dashboard\Models\Product\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        SELECT p.id, p.name, p.price,
//        (select count(k.id) from kardexs k where product_id = p.id and k.stock = 1)
//        FROM dashboard.products p;
        $products= DB::table('products')
            ->join('kardexs','kardexs.product_id','=','products.id')
            ->select('products.id','name','product_code','price','status', DB::raw('COUNT(kardexs.product_id) AS cant'))
            ->groupby('name')
            ->get();

//        $count= DB::table('kardexs')
//            ->select(DB::raw('COUNT(kardexs.product_id) AS cant)'))
//            ->where('stock','1')
//            ->get();
//        dd($count);

        return response()->json(['products' => $products],200);
    }


    public function store(Request $request)
    {

        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'name'      => 'required',
            'price'      => 'required',
            'product_code'     => 'required',
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un producto'],401);
            }
            // Si el validador pasa, almacenamos el comentario

            $productAux=DB::table('products')->where('product_code',$request->input('product_code'))->get();

            if(count($productAux) == 0 ){
                $productAux = new Product();
                $productAux->first_name= $request->input('name');
                $productAux->last_name= $request->input('price');
                $productAux->email= $request->input('product_code');
                //Falta agregar atributos de productos
                $productAux->save();
                return response()->json(['message' => 'El producto se agrego correctamente'],200);
            }

            return response()->json(['message' => 'El producto ya esta registrado'],400);

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
        try{
            $product = Product::find($id);
            if ($product !== null) {
                $kardexs=$product->kardexs;
                foreach ($kardexs as $kardex ){
                    $kardex->attributes;
                }
                return response()->json([
                    'message' => 'Mostrar detalles de producto',
                    'product'=> $product,
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
        try{
            $product = Product::find($id);
            if ($product !== null) {
                $product->name = $request->input('name');
                $product->price = $request->input('price');
                $product->image = $request->input('image');
                $product->product_code = $request->input('product_code');
                $product->save();

                return response()->json(['message' => 'Se actualizo correctamente'],200);
            }
            return \Response::json(['message' => 'No existe ese usuario'], 404);

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
    public function destroy($id)
    {
        try{
            $product = Product::find($id);
            if ($product == 1){
                $product->status=0;
                return response()->json(['message' => 'Se desactivo correctamente el producto'],200);
            }elseif ($product == 1){
                $product->status=1;
                return response()->json(['message' => 'Se activo correctamente el producto'],200);
            }
            return \Response::json(['message' => 'No existe el producto'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }
}
