<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Kardex\Attribute;
use Dashboard\Models\Kardex\Group_Attribute;
use Dashboard\Models\Kardex\Kardex;
use Dashboard\Models\Product\Type;
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
        $products= DB::table('products')
            ->join('kardexs','kardexs.product_id','=','products.id')
            ->select('products.id','products.image','name','product_code','price','status', DB::raw('COUNT(case kardexs.stock WHEN 1 then 1 else null end ) AS cant'))
            ->groupby('name')
            ->get();
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
            'image'     => 'required',
            'product_code'     => 'required',
            'status'        => 'required',
            'type_product'        => 'required',
            //falta validar los atributos
        ];

        //Se va a pasar datos del producto, attributos y su cantidad

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
                $product = new Product();
                $product->name= $request->input('name');
                $product->price= $request->input('price');
                $product->image= $request->input('image');
                $product->product_code= $request->input('product_code');
                $product->status= $request->input('status');
                $product->type_product= $request->input('type_product');
                $product->save();

                $groups=$request->input('groups');

                foreach($groups as $group){
                    $lastProduct = DB::table('products')
                        ->orderBy('id','desc')
                        ->first();
                    $this->addKardex($lastProduct->id,$group->cant,$group->attributes);
                }

                //Falta Agregar atributos de productos
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

//                SELECt DISTINCT  k.product_id,att.valor FROM kardexs k
//                join attribute_kardex a
//                on a.kardex_id=k.id
//                join attributes att
//                on att.id=a.attribute_id
//                where k.product_id=1;

//                $productAux= DB::table('kardexs')
//                        ->select('kardexs.id','types_attributes.name','attributes.valor')
//                        ->join('attribute_kardex','attribute_kardex.kardex_id','=','kardexs.id')
//                        ->join('attributes','attributes.id','=','attribute_kardex.attribute_id')
//                        ->join('types_attributes','types_attributes.id','=','attributes.type_id')
//                        ->where('kardexs.product_id','=',$id)
//                        ->orderby('kardexs.id')
//                        ->distinct('kardexs.id','types_attributes.name','attributes.valor')
//                        ->get();

                //$productAux2 = DB::select('')

                $kardexs=$product->kardexs;
                foreach ($kardexs as $kardex ){
                    $kardex->attributes;
                }
                return response()->json([
                    'message' => 'Mostrar detalles de producto',
                    'product'=> $kardexs,
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
        try{
            $product = Product::find($id);
            if ($product !== null) {
                $product->name = $request->input('name');
                $product->price = $request->input('price');
                $product->image = $request->input('image');
                $product->product_code = $request->input('product_code');
                $product->save();

                $product = DB::table('products')->where('name','=',$request->input('name'))->get();
                $cant = $request->input('cant');
                $attributes=$request->input('attributes');

                $this->addKardex($product->id,$cant,$attributes);
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
            if ($product->status == 1){
                $product->status = 0 ;
                $product->save();
                return response()->json(['message' => 'Se desactivo correctamente el producto'],200);
            }elseif ($product->status == 0){
                $product->status = 1;
                $product->save();
                return response()->json(['message' => 'Se activo correctamente el producto'],200);
            }
            return \Response::json(['message' => 'No existe el producto'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }
    
    public function types(){
        $types= Type::all();
        return response()->json(['$types' => $types],200);
    }

    private function addKardex($id,$cant,$attributes){


        $group= new Group_Attribute();
        $group->product_id=$id;
        $group->save();

        $lastGroup = DB::table('groups_attributes')
            ->where('product_id','=',$id)
            ->orderBy('id','desc')
            ->first();

        foreach($attributes as $attribute ){
            $a = new Attribute();
            $a->attribute_id = $attribute->attribute_id;
            $lastGroup->attributes()->save($a);
        }

        for ($i=0;$i<$cant;$i++){
            $k = new Kardex();
            $k->product_id=$id;
            $k->stock=true;
            $lastGroup->kardexs()->save($k);
        }

    }


}
