<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Movement;
use Dashboard\Models\Experimental\MovementOutFit;
use Dashboard\Models\Experimental\Product;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use Dashboard\Http\Requests;

class AuxMovementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:GOD,ADM,JVE');
        $this->middleware('auth:GOD,ADM', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = DB::table('auxproducts AS p')
            ->select('p.name','c.name AS color','s.name AS size','p.id','p.cod',DB::raw('count(p.cod) as cant'),
                DB::raw('case when d.price then d.price else p.cost_provider + p.utility end as price'),
                DB::raw('case when d.price then 1 else 0 end as status'))
            ->join('colors AS c','c.id','=','p.color_id')
            ->join('sizes AS s','s.id','=','p.size_id')
            ->leftJoin('settlements AS d','d.product_id','=','p.id')
            ->where('p.status','=',1)
            ->groupBy('p.name','s.name','c.name')
            ->orderBy('p.id','asc')
            ->get();

            if($products != null){
                return response()->json(['products' => $products],200);
            } else {
                return response()->json(['message' => 'No hay productos en existencia'],401);
            }

        } catch (\Exception $e) {
            return \Response::json(['message' => 'No se pudo listar los productos =('], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try{
            $movement = Movement::find($id);
            if ($movement != null){
                $product = Product::find($movement->product_id);
                $product->status=1;
                $product->save();
                $movement->delete();
                return response()->json(['message' => 'Se elimino correctamente el movimiento'],200);
            }
            return \Response::json(['message' => 'No existe ese movimiento'], 404);
        }catch (\ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
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
            4 => 'No se encontro cliente',
            5 => 'No es la talla',
            6 => 'No se encontro el código',
            7 => 'No llegamos al cliente',
            8 => 'Cliente cancelo su pedido',
            9 => 'Retorno-Cambio'
            ];

            if($move->status != 'vendido'){
                $move->situation = $situations[$request->input('situation')];
                $move->save();
                $movement = new Movement();
                $movement->situation = $situations[$request->input('situation')];
                $movement->date_shipment = $move->date_shipment;
                $movement->discount=$move->discount;
                $movement->status = 'Retornado';

                $product->movements()->save($movement);

                $product->status = 1;
                $product->save();
            } else {
                return response()->json(['message' => 'Este producto tiene un estado de: VENDIDO'],401);
            }
            return response()->json(['message' => 'El retorno se agrego correctamente'],200);

        } catch (\Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar una venta'], 500);
        }
    }

    public function product_out(Request $request){
        // Creamos las reglas de validación
        $rules = [
        'products'    => 'required|array',
        'requestDate'  => 'required|date',
        'shipmentDate'  => 'required|date',
        'codOrder'    => 'required',
        'products.*.id'    => 'required|integer|exists:auxproducts,id',
        'products.*.discount'    => 'required|numeric',
            // 'products.*.date'    => 'required|date',
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
            $movementsRes = array();

            foreach($products as $product){
                $prd = Product::with(["color","size"])->find($product['id']);
                if($prd->status == 1){
                    $movement = new Movement();
                    $movement->date_shipment =substr($request->input('shipmentDate'),0,10);
                    $movement->discount = $product['discount'];
                    $movement->status = 'salida';
                    $movement->date_request = substr($request->input('requestDate'),0,10);
                    $movement->cod_order = strtoupper($request->input('codOrder'));
                    $prd->movements()->save($movement);
                    $prd->status = 0;
                    $prd->save();

                    $response[] = $prd;
                    $movementsRes[] = $movement;
                }; 
            }

            return response()->json(['message' => 'Se genero la salida de los productos correctamente',
                'products' => $response, 'movements' => $movementsRes],200);

        } catch (\Exception $e) {
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
        if(\Validator::make($request->all(), ['date' => 'date'])->fails())
            return response()->json(['message' => 'Fecha invalida'], 401);

        $movement = Movement::find($id);

        if($movement == null)
            return response()->json(['message' => 'El movimiento no existe'], 404);

        $movement->situation = 'reprogramado';
        $movement->save();

        $newMovement = new Movement();
        $newMovement->product_id = $movement->product_id;
        $newMovement->date_shipment = $request->input('date');
        $newMovement->status = $movement->status;
        $newMovement->discount = $movement->discount;
        $newMovement->cod_order = $movement->cod_order;
        $newMovement->date_request = $movement->date_request;
        $newMovement->save(); 

        return response()->json(['message' => 'Se reprogramo la salida', 'movement' => $newMovement]);
    }

    public function sale(Request $request){
        // Creamos las reglas de validación
        $rules = [
        'id'    => 'required|integer',
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
            $move->situation="Vendido";

            if($move->status != 'Vendido'){
                $movement = new Movement();
                $movement->date_shipment = $move->date_shipment;
                $movement->discount=$move->discount;
                $movement->situation ="Vendido";
                $movement->status = 'Vendido';

                $product->movements()->save($movement);

                $product->status = 2;
                $product->push();
            } else {
                return response()->json(['message' => 'Este producto tiene un estado de: VENDIDO'],401);
            }
            return response()->json(['message' => 'La venta se agrego correctamente'],200);

        } catch (\Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar una venta'], 500);
        }
    }

    public function movementPending(){
        $products = Product::with(['bymovements','settlement','color','size'])
        ->select(array('id','cod','id as product_id','name','color_id','size_id','cost_provider','utility'))
        ->where('status',0)
        ->get();

        foreach ($products as $key => $product) {
            if(count($product->bymovements) == 0){
                $products->splice($key,1);
                continue;
            }
            $product->movement =  $product->bymovements[0];
            $product->price = $this->getPriceAttribute($product);
            $product->pricefinal = $this->getPriceFinalAttribute($product);
            $product->liquidation = $this->getLiquidationAttribute($product);
            unset($product->bymovements);
        }

        return response()->json(['products' => $products], 200);
    }

    /**
     *  Function movementPending helper
     */

    public function getPriceAttribute($product){
        $price = 0;
        if ($product->settlement == null){
            $price = $product->cost_provider + $product->utility;
        } else{
            $price = $product->settlement->price;
        }

        return $price;
    }

    public function getPriceFinalAttribute($product){
        return $product->price - $product->movement->discount;
    }

    public function getLiquidationAttribute($product){
        if($product->settlement == null){
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * END
     */
    

    public function movementDay(Request $request){
        $start = Carbon::today()->setTime(0,0,0);
        $request["date1"] = $start->toDateString();
        $request["date2"] = $start->toDateString();
        $movements = $this->queryReportMovement($request);
        return response()->json(['movements' => $movements],200);
    }

    public function movementOtherDay(Request $request){
        try {
            $start = Carbon::parse($request->input('date1'))->setTime(0,0,0);
            $request["date1"] = $start->toDateString();
        } catch(\InvalidArgumentException $e) {
            return response()->json(['message' => 'Fechas no validas, formato aceptado: Y-m-d'],401);
        }
        $request["date2"] = $start->toDateString();

        $movements = $this->queryReportMovement($request);
        return response()->json(['movements' => $movements],200);
    }

    public function movementDays(Request $request){

        try {
            $start = Carbon::parse($request->input('date1'))->setTime(0,0,0);
            $end = Carbon::parse($request->input('date2'))->setTime(23,59,59);
        } catch(\InvalidArgumentException $e) {
            return response()->json(['message' => 'Fechas no validas, formato aceptado: Y-m-d'],401);
        }

        $months = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'];

        $report = array();
        $movements = $this->queryReportMovement($request, $start, $end);
        $draw = $this->queryReportDraw($start, $request, $movements->unique("product")->lists("product"));

        $report['movements'] = $movements;
        $report['draw'] = $draw;
        $report['days'] = $start->daysInMonth;
        $report['month']['name'] = $months[$start->month];
        $report['month']['start'] = $start->copy()->firstOfMonth()->toDateString();
        $report['month']['end'] = $start->copy()->lastOfMonth()->toDateString();
        return response()->json($report,200);
    }

    public function movementDaysDownload(Request $request){

        $data = $this->queryReportMovement($request);

        foreach ($data as $row) {
            foreach ($row as $col => $value) {
                if($col == "liquidation"){
                    if($value == 0){
                        $row->sale = "N";
                    } else {
                        $row->sale = "L";                        
                    }
                }
                if ($col == "status")
                    $row->$col = strtolower($value);
            }
        }

        $date = date('Y-m-d');

        $title = "";
        if($request->has("date1") && $request->has("date2")){
            $start = Carbon::parse($request->input("date1"));
            $end = Carbon::parse($request->input("date2"));
            $title = 'Reporte de movimientos de productos entre las fechas: '.$start->toDateString().' y '.$end->toDateString();
        }
        if($request->has("order_date")){
            $order_date = Carbon::parse($request->input("order_date"));
            $title .= ($title?' - ':'').'Fecha de pedido: '.$order_date->toDateString();
        }
        if($request->has("order")){
            $title .= ($title?' - ':'').'N° Pedido: '.$request->input("order");
        }

        $columns = ['Fecha Pedido' => 'date_request',
            'Fecha Envio' => 'fecha',
            'Pedido' => 'cod_order',
            'Cod' => 'codigo',
            'Modelo' => 'product',
            'Color' => 'color',
            'Talla' => 'talla',
            'Estado' => 'status',
            'Venta' => 'sale',
            'Precio V.' => 'price',
            'Desc.' => 'discount',
            'Precio F.' => 'price_final'
        ];

        $view =  \View::make('pdf.templatePDF', compact('data','columns','title','date'))->render();

        $pdf = \PDF::loadHTML($view);
        $pdf->setOrientation('landscape');

        return $pdf->download();
    }

    /**
     *  Query de cosulta de moviemtnos
     *
     *  @param  Carbon\Carbon  $start
     *  @param  Carbon\Carbon  $send
     *  @return  Dashboard\Models\Experimental\Product
     */
    private function queryReportMovement(Request $request){

        $query = Product::from('auxproducts as p')
        ->select('m.created_at','m.date_shipment as fecha','m.status','m.discount','cod_order','date_request')
        ->addSelect('p.cod as codigo','p.name as product','p.cost_provider','p.utility')
        ->addSelect('c.name as color','s.name as talla')
        ->addSelect(DB::raw('p.cost_provider + p.utility  as price_real'))
        ->addSelect(DB::raw('case when dc.price then dc.price else p.cost_provider + p.utility end as price'))
        ->addSelect(DB::raw('case when dc.price then 1 else 0 end liquidation'))
        ->join('auxmovements as m','p.id','=','m.product_id')
        ->join('colors as c','c.id','=','p.color_id')
        ->join('sizes as s','s.id','=','p.size_id')
        ->leftJoin('settlements as dc','dc.product_id','=','p.id')
        ->orderby('m.date_shipment','asc');

        if($request->has('date1') && $request->has('date2')){
            $start = Carbon::parse($request->input('date1'));
            $end = Carbon::parse($request->input('date2'));
            $query->where('m.date_shipment','>=', $start->toDateString())
                ->where('m.date_shipment','<=', $end->toDateString());
        }

        if($request->has('status'))
            $query->where('m.status', $request->input('status'));

        if($request->has('name'))
            $query->where('p.name', $request->input('name'));

        if($request->has('size'))
            $query->where('p.size_id', $request->input('size'));

        if($request->has('color'))
            $query->where('p.color_id', $request->input('color'));

        if($request->has('provider'))
            $query->where('p.provider_id', $request->input('provider'));

        if($request->has('order_date')){
            $order_date = Carbon::parse($request->input('order_date'));
            $query->where('m.date_request', $order_date->toDateString());
        }

        if($request->has('order'))
            $query->where('m.cod_order', $request->input('order'));

        // dd([$start, $end, $query->toSql()]);

        $products = $query->get();

        foreach ($products as $product) {
            $product->price_final = $product->price - $product->discount;
        }

        return $products;
    }

    /**
     *    Reporta de movimientos para la vista grafica por producto por mes
     *
     *    @param  Carbon\Carbon  $date
     *    @param  Illuminate\Http\Request  $request
     *    @param  Array:string  $produts
     *    @return  Array:Array:string,Array:Illuminate\Support\Facades\DB
     */
    private function queryReportDraw(Carbon $date, Request $request, $products){
        $ini = $date->copy()->firstOfMonth()->setTime(0,0,0);
        $fin = $date->copy()->lastOfMonth()->addDay()->setTime(0,0,0);
        $draw = array();

        $query = DB::table('auxproducts as p')
            ->select(DB::raw('DAY(m.date_shipment) as fecha'),DB::raw('count(p.cod) as quantity'))
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('m.date_shipment','>=',$ini->toDateString())
            ->where('m.date_shipment','<=',$fin->toDateString())
            ->groupby('m.date_shipment');

        if($request->has("status"))
            $query->where('m.status', $request->input("status"));

        foreach ($products as $value) {
            $temp = array();
            $temp['name'] = $value;
            $queryTemp = clone $query;
            $temp['data'] = $queryTemp->where('p.name', $value)->get();
            array_push($draw, $temp);
        }

        return $draw;
    }

    public function move_day(){
        $date = Carbon::today();
        $date2 = $date->copy()->addDay();

        $salida = Movement::where('status','=','salida')->where('situation','=',null)->where('created_at','>=',$date->toDateTimeString())->where('created_at','<',$date2->toDateTimeString())->count();
        $salidaOutFits = MovementOutFit::with(['products'])
        ->where('status','=','salida')->where('respond','=',0)->where('created_at','>=',$date->toDateTimeString())->where('created_at','<',$date2->toDateTimeString())->get();
        $salidaOut=0;
        foreach($salidaOutFits as $salidaOutFit){
            $salidaOut = $salidaOut + count($salidaOutFit->products);
        }

        $vendido = Movement::where('status','=','Vendido')->where('created_at','>=',$date->toDateTimeString())->where('created_at','<',$date2->toDateTimeString())->count();
        $vendidoOutFits = MovementOutFit::with(['products'])
        ->where('status','=','vendido')->where('created_at','>=',$date->toDateTimeString())->where('created_at','<',$date2->toDateTimeString())->get();
        $vendidoOut=0;
        foreach($vendidoOutFits as $vendidoOutFit){
            $vendidoOut = $vendidoOut + count($vendidoOutFit->products);
        }

        $retornado = Movement::where('status','=','Retornado')->where('created_at','>=',$date->toDateTimeString())->where('created_at','<',$date2->toDateTimeString())->count();
        $retornadooOutFits = MovementOutFit::with(['products'])
        ->where('status','=','retornado')->where('created_at','>=',$date->toDateTimeString())->where('created_at','<',$date2->toDateTimeString())->get();
        $retornadoOut=0;
        foreach($retornadooOutFits as $retornadoOutFit){
            $retornadoOut = $retornadoOut + count($retornadoOutFit->products);
        }

        $stock = Product::where('status',1)->count();
        $res = Product::where('status',3)->count();
        return response()->json(['data' => [ 'sal' => $salida + $salidaOut, 'ven' => $vendido + $vendidoOut, 'ret' => $retornado + $retornadoOut, 'stock' => $stock+$res,'res' => $res]],200);
    }

//    public function move_day_today(){
//        $date = Carbon::today();
//        $date2 = $date->copy()->addDay();
//
//        $salida = Movement::where('status','=','salida')->where('situation','=',null)->where('date_shipment','>=',$date->toDateTimeString())->where('date_shipment','<',$date2->toDateTimeString())->count();
//        $vendido = Movement::where('status','=','Vendido')->where('date_shipment','>=',$date->toDateTimeString())->where('date_shipment','<',$date2->toDateTimeString())->count();
//        $retornado = Movement::where('status','=','Retornado')->where('date_shipment','>=',$date->toDateTimeString())->where('date_shipment','<',$date2->toDateTimeString())->count();
//        $stock = Product::where('status',1)->count();
//
//        return response()->json(['data' => [ 'sal' => $salida, 'ven' => $vendido, 'ret' => $retornado, 'stock' => $stock]],200);
//    }
    
    public function consolidadoOut(){
        $date = Carbon::today();
        $date2 = $date->copy()->addDay();
        $status = 'vendido';

        $vendidoOutFits = MovementOutFit::with(['products','outfit'])
        ->where('status','=',$status)->where('created_at','>=',$date->toDateTimeString())->where('created_at','<',$date2->toDateTimeString())->get();

//        return $vendidoOutFits;

        $cost=0;
        $util=0;
        $price=0;
        $cantP=0;
        foreach($vendidoOutFits as $vendidoOutFit){
            $cantP=$cantP+count($vendidoOutFit->products);
            foreach ($vendidoOutFit->products as $product){
                $cost=$cost+$product->cost_provider;
            }
            $price=$price+$vendidoOutFit->outfit->price;
        }
        $util=$price-$cost;


        return response()->json(['data'=>['cant'=>$cantP,'uti'=>$util,'price'=>$price,'desct'=>0]]);
    }

    public function consolidado(){
        $date1 = Carbon::today();
        $date2 = $date1->copy()->addDay();
        $status = "Vendido";

        $movements = DB::table('auxproducts AS p')
        ->select(DB::raw('count(p.id) as cant'),DB::raw('case when sum(p.utility-m.discount) then sum(p.utility-m.discount) else 0 end as uti')
            ,DB::raw('case when sum(p.cost_provider+p.utility-m.discount) then sum(p.cost_provider+p.utility-m.discount) else 0 end as price')
            ,DB::raw('case when sum(m.discount) then sum(m.discount) else 0 end as desct'))
        ->join('auxmovements AS m','m.product_id','=','p.id')
        ->where('p.status','=',2)
            // ->where('m.situation','=',null)
        ->where('m.status','like','%'.$status.'%')
        ->where(DB::raw('DATE(m.created_at)'),'>=',$date1->toDateString())
        ->where(DB::raw('DATE(m.created_at)'),'<',$date2->toDateString())
//            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
//            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
//            ->groupby('m.id')
        ->get();

//        $products = DB::table('auxproducts AS p')
//            ->select('p.cod',DB::raw('max(m.date_shipment) as date_shipment'),'p.status','p.id as product_id',
//                'p.name','s.name as size','c.name as color','m.id as movement_id',
//                DB::raw('max(m.created_at) as date'),DB::raw('p.cost_provider+utility-m.discount as price'))
//            ->join('auxmovements AS m','m.product_id','=','p.id')
//            ->join('colors AS c','c.id','=','p.color_id')
//            ->join('sizes AS s','s.id','=','p.size_id')
//            ->where('p.status','=',0)
//            ->where('m.status','=','salida')
//            ->groupby('p.id')->get();

//        $movements=DB::table('auxproducts as p')
//            ->select('m.date_shipment as fecha','p.cod as codigo','p.name as product','c.name as color','s.name as talla','m.status')
//            ->join('auxmovements as m','p.id','=','m.product_id')
//            ->join('colors as c','c.id','=','p.color_id')
//            ->join('sizes as s','s.id','=','p.size_id')
//            ->where('m.status','like','%'.$status.'%')
//            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
//            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
//            ->orderby('p.name','c.name','s.name')
//            ->get();

        return response()->json(['data'=>$movements[0]]);
    }
    
    public function move_day_outfit(){
        //Falta crear consulta de movimientos de outfit
    }

    public function providertest(){
        $date1 = Carbon::today();
        $date2 = $date1->copy()->addDay();
        $status = "salida";

        $movements = DB::table('auxproducts AS p')
        ->select('pv.name',DB::raw('count(m.id) as cant'),DB::raw('sum(p.utility-m.discount) as uti'),DB::raw('sum(p.cost_provider+p.utility-m.discount) as price'),DB::raw('sum(m.discount) as desct'))
        ->join('auxmovements AS m','m.product_id','=','p.id')
        ->join('providers AS pv','pv.id','=','p.provider_id')
        ->where('p.status','=',0)
        ->where('m.situation','=',null)
        ->where('m.status','like','%'.$status.'%')
        ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
//            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
        ->groupby('pv.id')
        ->get();

        return response()->json(['movements',$movements],200);
    }



    public function get_cod_prod(Request $request){
        $rules = [
        'id'    =>  'required|integer'
        ];

        if(\Validator::make($request->all(), $rules)->fails())
            return response()->json(['message' => 'No posee los campos necesarios para realizar una consulta'], 404);

        $prd = Product::find($request->input('id'));

        $product =  Product::select('id','cod')
        ->where('name','=',$prd->name)
        ->where('color_id','=',$prd->color_id)
        ->where('size_id','=',$prd->size_id)
        ->where('status','=',1)
        ->orderBy('cod','asc')
        ->get();

        return response()->json(['codes' => $product],200);
    }

    /**
     *    Listar productos a despachar por fecha
     *
     *    @param  Illuminate\Http\Request  $request
     *    @return  Illuminate\Http\Response
     */ 
    public function getDispatchForDate(Request $request){
        if($request->has("date")){
            if(\Validator::make($request->all(),["date" => "required|date"])->fails())
                return response()->json(["message" => "Fecha de busqueda invalida."]);

            $date = Carbon::parse($request->input("date"));
        } else {
            $date = Carbon::now();
        }

        $products = $this->dispatchForDate($date);

        return response()->json(['products' => $products], 200);

    }

    /**
     *    Descarga de pdf para ficha de despacho por fecha
     *
     *    @param  Illuminate\Http\Request  $request
     *    @return  ArrayBuffer
     */
    public function dispatchForDateDownload(Request $request){
        if($request->has("date")){
            if(\Validator::make($request->all(),["date" => "required|date"])->fails())
                return response()->json(["message" => "Fecha de busqueda invalida."]);

            $dateFind = Carbon::parse($request->input("date"));
        } else {
            $dateFind = Carbon::now();
        }

        $data = $this->dispatchForDate($dateFind);

        foreach ($data as $row) {
            $row->movement_date_request =  $row->movement->date_request;
            $row->movement_date_shipment =  $row->movement->date_shipment;
            $row->movement_discount =  $row->movement->discount;
            $row->movement_cod_order =  $row->movement->cod_order;
            $row->color_name =  $row->color->name;
            $row->size_name =  $row->size->name;
            if($row->movement->discount == 0){
                $row->sale = "Normal";
            } else {
                $row->sale = "Liquidacion";
            }

        }

        $date = date('Y-m-d');
        $title = 'Ficha de despacho para la fecha: '.$dateFind->toDateString();
        $columns = ['Orden' => 'movement_cod_order',
        'Fecha Pedido' => 'movement_date_request',
        'Fecha Salida' => 'movement_date_shipment',
        'Cod' => 'cod',
        'Modelo' => 'name',
        'Color' => 'color_name',
        'Talla' => 'size_name',
        'Venta' => 'sale',
        'Precio V.' => 'price',
        'Desc.' => 'movement_discount',
        'Precio F.' => 'pricefinal'
        ];

        $view =  \View::make('pdf.templatePDF', compact('data','columns','title','date'))->render();

        $pdf = \PDF::loadHTML($view);
        $pdf->setOrientation('landscape');

        return $pdf->download();
    }

    /**
     *    Product Collection para despacho por fecha
     *
     *    @param  Carbon\Carbon  $date
     *    @return  Dashboard\Models\Experimental\Product
     */
    private function dispatchForDate(Carbon $date){
        $date = $date->toDateString();

        $products = Product::with(['bymovements','settlement','color','size'])
        ->select(array('id','cod','id as product_id','name','color_id','size_id','cost_provider','utility'))
        ->where('status',0)
        ->get();

        $filter = array();
        foreach ($products as $key => $product) {

            if(count($product->bymovements) == 0)continue;

            if($product->bymovements[0]->date_shipment == $date){
                $product->movement =  $product->bymovements[0];
                $product->price = $this->getPriceAttribute($product);
                $product->pricefinal = $this->getPriceFinalAttribute($product);
                $product->liquidation = $this->getLiquidationAttribute($product);
                unset($product->bymovements);

                array_push($filter,$product);
            }

        }

        return $filter;
    }

}
