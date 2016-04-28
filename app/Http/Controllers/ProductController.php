<?php

namespace Dashboard\Http\Controllers;

//use Dashboard\Models\Kardex\Attribute;
use Dashboard\Dashboard\Models\Kardex\AttributesKardex;
use Dashboard\Models\Kardex\GroupAttribute;
use Dashboard\Models\Kardex\Kardex;
use Dashboard\Models\Product\TypeProduct;
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

        // Creamos las reglas de validación
        $rules = [
            'name'          => 'required',
            'price'         => 'required',
            'img'           => 'required',
            'groupAttr'     => 'required',
            'status'        => 'required',
            'type_product_id'   => 'required',
            'groupAttr.*.quantity'   => 'required'
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


            $produt_code = $this->product_code_generate(); // obetener un codigo unico

            $image = $request->file('img'); // referencia a la imagen
            $image_name = $produt_code.'.'.$image->getClientOriginalExtension();
            $image_folder = 'img/products/';
            $image->move(public_path($image_folder), $image_name); // moviendo imagen a images folder

            $product = new Product();
            $product->name= $request->input('name');
            $product->price= $request->input('price');
            $product->image= $image_name;
            $product->product_code = $produt_code;
            $product->status= $request->input('status');
            $product->type_product_id= $request->input('type_product_id');
            $product->save();

            $groups = json_decode($request->input('groupAttr'), true);

            foreach($groups as $group){
                $this->addKardex($product->id, $group['quantity'] , $group['attributes']);
            }
            //Falta Agregar atributos de productos
            return '}]),'.response()->json(['message' => 'El producto se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }

    private function product_code_generate(){
        $temp = (string)rand(100000,999999).(string)rand(100000,999999);
        $product = DB::table('products')->where('product_code','=', $temp)->first();
        if (!$product)
            return $temp;
        else
            return $this->product_code_generate();
    }

    private function addKardex($id,$cant,$attributes){
        $group= new GroupAttribute();
        $group->product_id=$id;
        $group->save();

        foreach($attributes as $attribute ){
            $k_attr = new AttributesKardex();
            $k_attr->attribute_id = $attribute['val_id'];
            $group->attributes()->save($k_attr);
        }

        for ($i=0;$i<$cant;$i++){
            $kard = new Kardex();
            $kard->product_id=$id;
            $kard->stock=true;
            $group->kardexs()->save($kard);
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
        $types= TypeProduct::all();
        return response()->json(['types' => $types],200);
    }

    public function group_attributes($id){
        $attributes =  DB::table('groups_attributes')
            ->select(array('attributes_kardexs.group_attribute_id','types_attributes.name', 'attributes.valor'))
            ->join('attributes_kardexs', function ($join) {
                $join->on('attributes_kardexs.group_attribute_id', '=', 'groups_attributes.id');
            })
            ->join('attributes', function ($join) {
                $join->on('attributes_kardexs.attribute_id', '=', 'attributes.id');
            })
            ->join('types_attributes', function ($join) {
                $join->on('attributes.type_id', '=', 'types_attributes.id');
            })
            ->where('product_id',$id)
            ->get();

        $result = array();
        foreach($attributes as $d){
            if(!isset($result[$d->group_attribute_id])){
                $result[$d->group_attribute_id] = array();
            }
            $result[$d->group_attribute_id][] = $d;
        }

        return response()->json(['grp_attributes' => $result],200);
    }

    public function type_products($id){
        $type = TypeProduct::find($id) ;
        $type->products;
        if (!is_null($type))
            return response()->json([ 'type' => $type ],200);
        else
            return response()->json(['message' => 'No se encuentraron productos con el termino de busqueda'],404);
    }
}
