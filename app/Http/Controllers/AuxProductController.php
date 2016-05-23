<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Dashboard\Events\ProductWasCreated;
use Dashboard\Models\Experimental\Color;
use Dashboard\Models\Experimental\Provider;
use Dashboard\Models\Experimental\Size;
use Dashboard\Models\Experimental\Type;
use Illuminate\Support\Facades\Event;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;
use \DB;
use \Exception;
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
        $products = DB::table('auxproducts as p')
                        ->select(DB::raw('DATE_FORMAT(p.created_at,\'%d-%m-%Y\') as date'),'p.id','p.cod','p.name','c.name as color','c.name as size','p.name as provider',DB::raw('GROUP_CONCAT(t.name SEPARATOR \' - \') as types'))
                        ->join('types_auxproducts as tp','tp.product_id','=','p.id')
                        ->join('types as t','t.id','=','tp.type_id')
                        ->join('colors as c','c.id','=','p.color_id')
                        ->join('sizes as s','s.id','=','p.size_id')
                        ->join('providers as pv','pv.id','=','p.provider_id')
                        ->groupBy('cod')
                        ->get();

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

                $types = $request->input('types');

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

                    foreach($types as $type){
                        $tipo = Type::find($type['id']);
                        $product->types()->attach($tipo);
                    }
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

        }catch (Exception $e){
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
//        $rules = [
//            'cod'          => 'required',
//            'provider_id'         => 'required',
//            'color_id'           => 'required',
//            'size_id'     => 'required',
//            'name'   => 'required',
//            'day'        => 'required',
//            'count'        => 'required',
//            'cant'        => 'required',
//        ];
//
//        //Se va a pasar datos del producto, attributos y su cantidad
//        try {
//            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
//            // con los errores
//            $validator = \Validator::make($request->all(), $rules);
//            if ($validator->fails()) {
//                return response()->json(['message' => 'No posee todo los campos necesario para crear un producto'], 401);
//            }
//
//        } catch (Exception $e) {
//            // Si algo sale mal devolvemos un error.
//            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
//        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auxproduct= Product::find($id);
        if($auxproduct != null ){
            $resultado=$auxproduct->movements;

            if($resultado->count()==0){
                $auxproduct->delete();
                return response()->json(['message'=>'El producto correctamente']);
            }else{
                return response()->json(['message'=>'No se puede eliminar este producto porque posee movimiento asociados']);
            }
        }else{
            return response()->json(['message'=>'No existe este producto']);
        }
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

            $provider = Provider::where('name','=',$request->input('name'))->exists();

            if(!$provider){

                $types = json_decode($request->input('types'), true);

                $proveedor = new Provider();
                $proveedor->name = $request->input('name');
                $proveedor->save();
                return response()->json(['message' => 'El nuevo proveedor se agrego correctamente'],200);

            } else {

                return response()->json(['message' => 'El nombre del proveedor ya existe'],401);
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

            $color = Color::where('name','=',$request->input('name'))->exists();

            if(!$color){
                
                $c = new Color();
                $c->name = $request->input('name');
                $c->save();
                return response()->json(['message' => 'El nuevo color se agrego correctamente'],200);

            } else {

                return response()->json(['message' => 'El nombre del color ya existe'],401);
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

        $data = Array();
        foreach ($codigos as $codigo){
            if($codigo->cod - $i != 1){
                if ($i !=0) {
                    $cant = $codigo->cod - $i;
                    $codAux = $i;
                    for ($z = 1; $z < $cant; $z++) {
                        $data[$j] = $codAux + $z;
                        $j++;
                    }
                }else{
                    $cod=$codigo->cod;
                    if($cod!=1) {
                        for ($p = 1; $p < $cod; $p++) {
                            $data[$j] = $p;
                            $j++;
                        }
                    }
                }
            }
            $i=$codigo->cod;
        }
        return response()->json(['codes' => $data],200);
    }

    public function cantPro(){

        $cant = DB::table('providers as pr')
            ->select('pr.name',DB::raw('count(p.id) as cant'))
            ->join('auxproducts as p','pr.id','=','p.provider_id')
            ->where('p.status',1)
            ->groupby('pr.name')->get();
        
        return response()->json(['products'=>$cant],200);
    }

    public function stockProd(){

        $stock = DB::table('auxproducts as p')
                ->select('p.id','p.name','c.name as color','s.name as size',DB::raw('count(p.name) as cantP',''))
                ->join('colors as c','c.id','=','p.color_id')
                ->join('sizes as s','s.id','=','p.size_id')
                ->where('p.status','=',1)
                ->groupby('p.name','c.name','s.name')->get();

        return response()->json(['stock' => $stock],200);
        
    }

    public function stockProdType($id) {
        $types = DB::table('auxproducts as p')
            ->select(DB::raw('GROUP_CONCAT(t.name SEPARATOR \' - \') as types'))
            ->join('types_auxproducts as tp','tp.product_id','=','p.id')
            ->join('types as t','t.id','=','tp.type_id')
            ->where('p.id','=',$id)
            ->groupBy('cod')
            ->first();

        return response()->json($types,200);
    }

    public function stockIni(){

        $stock = DB::table('auxproducts as p')
                ->select(DB::raw('count(p.name)'))
                ->where('p.status','=','1')->get();

        return response()->json(['stock'=>$stock],200);
    }

    public function prodSize(){

        $productSize = DB::table('sizes as s')
                ->select('p.name','s.name as size',DB::raw('count(p.size_id) as cant'))
                ->join('auxproducts as p','s.id','=','p.size_id')
                ->where('p.status','=','1')
                ->groupby('p.name','s.name')->get();

        return response()->json(['products'=>$productSize],200);

    }

    public function prodColor(){

        $productColor = DB::table('colors as c')
            ->select('p.name','c.name as color',DB::raw('count(p.color_id) as cant'))
            ->join('auxproducts as p','c.id','=','p.color_id')
            ->where('p.status','=','1')
            ->groupby('p.name','c.name')->get();
        return response()->json(['products'=>$productColor],200);

    }

    public function prodOutProvider()
    {
        $productOutProvider = DB::table('providers as pr')
            ->select('pr.name as provider', 'p.name', DB::raw('count(m.id) as cant'))
            ->join('auxproducts as p', 'pr.id', '=', 'p.provider_id')
            ->join('auxmovements as m', 'p.id', '=', 'm.product_id')
            ->groupby('pr.name', 'p.name')
            ->orderby('cant', 'desc')->get();

        return response()->json(['products' => $productOutProvider], 200);
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

    public function alarm(){
        $products = DB::table('alarms as a')
                ->select('p.name','c.name as color','s.name as talla','p.created_at','a.day','a.count',DB::raw('count(p.id) as cant'))
                ->join('auxproducts as p','a.id','=','p.alarm_id')
                ->join('auxmovements as m','p.id','=','m.product_id')
                ->join('colors as c','c.id','=','p.color_id')
                ->join('sizes as s','s.id','=','p.size_id')
                ->where('m.status','=','vendido')
                ->groupby('p.name')->get();
        $j=0;
        $alarms = array();
        foreach ($products as $product){
            $dateNow=Carbon::now();
            $date=Carbon::createFromFormat('Y-m-d H:i:s', $product->created_at);

            $diff=(int)$dateNow->diffInDays($date);

            if($diff > (int)$product->day){
                if((int)$product->cant < (int)$product->count){
                    $alarms[$j] = array('name' => $product->name);
                     $j++;
                }
            }
        }
        if(!empty($data)){
            return response()->json([ 'alarms' => $alarms ],200);
        }else{
            return response()->json([ 'message' => 'Por el momento no hay alarmas' ],401);
        }
    }

    public function listProduct(Request $request){
        try{
            if(!\Validator::make($request->all(), ['name'  => 'required', 'size' => 'required'])->fails()){
                return response()->json(\DB::table('auxproducts as p')
                    ->select('c.id', 'c.name')
                    ->join('colors as c','c.id','=','p.size_id')
                    ->where('p.name','=',$request->input('name'))
                    ->where('p.size_id','=',$request->input('size'))
                    ->groupBy('c.name')
                    ->get(),200);
            } else if (!\Validator::make($request->all(), ['name'  => 'required'])->fails()) {
                return response()->json(\DB::table('auxproducts as p')
                    ->select('s.id', 's.name')
                    ->join('sizes as s','s.id','=','p.size_id')
                    ->where('p.name','=',$request->input('name'))
                    ->groupBy('s.name')
                    ->get(),200);
            } else {
                return response()->json(\DB::table('auxproducts')->select('id','name')->groupBy('name')->get(),200);
            }
        } catch (\Exception $e) {
            return \Response::json(['message' => 'Ocurrio un problema =('], 500);
        }
    }
}
