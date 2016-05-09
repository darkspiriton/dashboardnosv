<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Movement;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            $cant = DB::table('auxproducts as p')
                ->join('sizes as s','p.size_id','=','s.id')
                ->join('colors as c','p.color_id','=','c.id')
                ->where('p.status','=',1)
                ->groupby('p.name','s.name','c.name')
                ->orderby('p.id','asc')->get();

            $products = DB::table('auxproducts AS p')
                ->select('p.name','c.name AS color','s.name AS size','p.id','p.cod',DB::raw('count(p.cod) as cant'))
                ->join('colors AS c','c.id','=','p.color_id')
                ->join('sizes AS s','s.id','=','p.size_id')
                //->leftjoin('auxmovements AS m','m.product_id','=','p.id')
                ->where('p.status','=',1)
                ->groupby('p.name','s.name','c.name')
                ->orderby('p.id','asc')
                //->unionAll($cant)
                ->get();

//            select count(p.name) from auxproducts p
//            join sizes s on p.size_id=s.id
//            join colors c on p.color_id=c.id
//            where p.status=1
//            group by p.name,s.name,c.name
//            order by p.id asc;

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
        // Creamos las reglas de validación
        $rules = [
            'id'    => 'required',
            'situation' =>  'required'
        ];

        //Se va a pasar datos del movimiento
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un movimiento'],401);
            }

            $product = Product::find($request->input('id'));

            if ($product == null){
                return response()->json(['message' => 'El producto no existe'],401);
            }

            $move = $product->movements->first();
            $situations = [ 1 => 'No le gusto',
                            2 => 'La foto no es igual al producto',
                            3 => 'Producto dañado',
                            4 => 'No se encontro cliente'
                        ];

            if($move->status != 'vendido'){
                $movement = new Movement();
                $movement->situation = $situations[$request->input('situation')];
                $movement->date_shipment = $move->date_shipment;
                $movement->status = 'Retornado';

                $product->movements()->save($movement);

                $product->status = 1;
                $product->save();
            } else {
                return response()->json(['message' => 'Este producto tiene un estado de: VENDIDO'],401);
            }
            return response()->json(['message' => 'El retorno se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar una venta'], 500);
        }
    }

    public function product_out(Request $request){
        // Creamos las reglas de validación
        $rules = [
            'products'    => 'required',
        ];

        //Se va a pasar datos del movimiento
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un movimiento'],401);
            }

            $products = $request->input('products');

            $response = array();

            foreach($products as $product){
                $prd = Product::find($product['id']);
                if($prd->status == 1){
                    $movement = new Movement();
                    $movement->date_shipment = $product['date'];
                    $movement->status = 'salida';
                    $prd->movements()->save($movement);
                    $prd->status = 0;
                    $prd->save();

                };
                $response[] = $prd;
            }

            return response()->json(['message' => 'El producto se agrego correctamente',
                                        'products' => $response],200);

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

    public function sale(Request $request){
        // Creamos las reglas de validación
        $rules = [
            'id'    => 'required',
        ];

        //Se va a pasar datos del movimiento
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un movimiento'],401);
            }

            $product = Product::find($request->input('id'));

            if ($product == null){
                return response()->json(['message' => 'El producto no existe'],401);
            }

            $move = $product->movements->first();

            if($move->status != 'vendido'){
                $movement = new Movement();
                $movement->date_shipment = $move->date_shipment;
                $movement->status = 'Vendido';

                $product->movements()->save($movement);

                $product->status = 2;
                $product->save();
            } else {
                return response()->json(['message' => 'Este producto tiene un estado de: VENDIDO'],401);
            }
            return response()->json(['message' => 'La venta se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar una venta'], 500);
        }
    }

    public function movementPending(){
        $products = DB::table('auxproducts AS p')
            ->select('p.cod', 'm.date_shipment','p.status','p.id as product_id','p.name','s.name as size','c.name as color','m.id as movement_id',DB::raw('max(m.created_at) as date'))
            ->join('auxmovements AS m','m.product_id','=','p.id')
            ->join('colors AS c','c.id','=','p.color_id')
            ->join('sizes AS s','s.id','=','p.size_id')
            ->where('p.status','=',0)
            ->groupby('p.id')->get();
        return \Response::json(['products' => $products], 200);
    }

    public function movementDay(Request $request){

        $date1=Carbon::createFromFormat('Y-m-d', $request->input('date1'));
        $date2=$date1->addDays(1);

        //$movements=$this->entrefechas($date1,$date2);
        
        return response()->json(['movements' => $this->entrefechas($date1,$date2)],200);
    }

    public function movementDays (Request $request){

        $date1=Carbon::createFromFormat('Y-m-d', $request->input('date1'));
        $date2=Carbon::createFromFormat('Y-m-d', $request->input('date2'));

        //$movements=$this->entrefechas($date1,$date2);

        return response()->json(['movements' => $this->entrefechas($date1,$date2)],200);
    }

    private function entrefechas($date1,$date2){

//        select p.cod codigo,p.name product, c.name color, s.name talla, max(m.date_shipment) fecha from auxproducts p
//        join auxmovements m on p.id=m.product_id
//        join colors c on c.id=p.color_id
//        join sizes s on s.id=p.size_id
//        where p.status='1' and m.status='aut' and DATE(m.date_shipment) > '1984-12-20' and DATE(m.date_shipment) < '1984-12-22'
//        group by p.name, c.name, s.name;

        $movements=DB::table('auxproducts as p')
            ->select('p.cod as codigo','p.name as product','c.name as color','s.name as talla','m.date_shipment as fecha')
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('p.status','=','1')
            ->where('m.status','=','aut')
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
            ->groupby('p.name','c.name','s.name')
            ->get();

        return $movements;
    }

    

    public function move_day(){
        $salida = Product::where('status',0)->count();
        $vendido = Product::where('status',3)->count();
        $stock = Product::where('status',1)->count();

        return response()->json(['data' => [ 'sal' => $salida, 'ven' => $vendido, 'stock' => $stock]],200);
    }


}
