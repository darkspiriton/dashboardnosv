<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Events\ProductWasCreated;
use Dashboard\Models\Experimental\Color;
use Dashboard\Models\Experimental\Provider;
use Dashboard\Models\Experimental\Size;
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
            $product->provider;
        }
        return response()->json(['products' => $products],200);
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

    public function getProviders(){
        $providers = Provider::all();

        return response()->json(['providers' => $providers ],200);
    }

    public function getSizes(){
        $sizes = Size::all();

        return response()->json(['sizes' => $sizes],200);
    }

    public function getColors(){
        $colors = Color::all();

        return response()->json(['colors' => $colors],200);
    }

}
