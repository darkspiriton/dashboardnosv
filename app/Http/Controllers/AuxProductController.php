<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Events\ProductWasCreated;
use Dashboard\Models\Experimental\Color;
use Dashboard\Models\Experimental\Provider;
use Illuminate\Support\Facades\Event;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dashboard\Models\Experimental\Alarm;

use Dashboard\Http\Requests;

class AuxProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::all();
        foreach ($products as $product){
            $product->size;
            $product->color;
        }
        return response()->json(['products',$products],200);
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
            'cod'          => 'required',
            'provider_id'         => 'required',
            'color_id'           => 'required',
            'size_id'     => 'required',
            'name'   => 'required',
            'day'        => 'required',
            'count'        => 'required',
            'cant'        => 'required',
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un producto'],401);
            }

            $product = DB::table('auxproducts')
                        ->where('cod','=',$request->input('cod'))
                        ->get();
            if($product == null){
                $data['day'] = $request->input('day');
                $data['count'] = $request->input('count');

                $data['provider_id'] = $request->input('provider_id');
                $data['color_id'] = $request->input('color_id');
                $data['size_id'] = $request->input('size_id');
                $data['name'] = $request->input('name');
                $data['cant'] = $request->input('cant');
                $data['cod'] = $request->input('cod');

                // Si el validador pasa, almacenamos la alarma
                $alarm = new Alarm();
                $alarm->day = $data['day'];
                $alarm->count = $data['count'];
                $alarm->save();

                $cant=$data['cant'];
                $cod= $data['cod'];
                for($i=0;$i<$cant;$i++){
                    $product = new Product();
                    $product->cod= $cod+$i;
                    $product->provider_id= $data['provider_id'];
                    $product->color_id= $data['color_id'];
                    $product->size_id= $data['size_id'];
                    $product->alarm_id= $alarm->id;
                    $product->name= $data['name'];
                    $product->status= 1;
                    $product->save();
                }

                //Event::fire(new ProductWasCreated($data));

                return response()->json(['message' => 'El producto se agrego correctamente'],200);
            } else {
                return response()->json(['message' => 'El código del producto ya existe'],401);
            }



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
                $product->movements;
                $product->provider;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    public function setProvider(Request $request){
        // Creamos las reglas de validación
        $rules = [
            'name'          => 'required',
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un nuevo proveedor'],401);
            }

            $provider = DB::table('providers')
                ->where('name','=',$request->input('name'))
                ->get();

            if($provider == null){

                $proveedor = new Provider();
                $proveedor->name = $request->input('name');
                return response()->json(['message' => 'El nuevo proveedor se agrego correctamente'],200);

            } else {

                return response()->json(['message' => 'El nombre del proveedor ya existe'],200);
            }

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar proveedor'], 500);
        }
    }

    public function setColor(Request $request){
        // Creamos las reglas de validación
        $rules = [
            'name'          => 'required',
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un nuevo color'],401);
            }

            $color = DB::table('colors')
                ->where('name','=',$request->input('name'))
                ->get();

            if($color == null){

                $c = new Color();
                $c->name = $request->input('name');
                return response()->json(['message' => 'El nuevo color se agrego correctamente'],200);

            } else {

                return response()->json(['message' => 'El nombre del color ya existe'],200);
            }

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar color'], 500);
        }
    }

    public function  getCod(){
        $codigos = DB::table('auxproducts')
            ->select('cod')
            ->orderby('cod','asc')
            ->get();
        $i=0;
        $j=0;
        foreach ($codigos as $codigo){
            if($codigo->cod - $i == 1){

            }else{
                $cant = $codigo->cod - $i;
                $codAux=$codigo->cod;
                for($z=1;$z<$cant;$z++){
                    $data[$j] = $codAux + $z ;
                    $j++;
                }
            }
            $i=$codigo->cod;
        }
       // dd($codigos);
        return response()->json(['codigos' =>$data],200);
    }

    public function cantProduct(){

    }


}
