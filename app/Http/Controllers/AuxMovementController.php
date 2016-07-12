<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Movement;
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
        $this->middleware('auth:GOD' , ['only' => ['movementDay','movementDays','movementDaysDownload']]);
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
                            5 => 'No es la talla'
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
            'products.*.id'    => 'required|integer|exists:auxproducts,id',
            'products.*.discount'    => 'required|numeric',
            'products.*.date'    => 'required|date',
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
                $prd = Product::find($product['id']);
                if($prd->status == 1){
                    $movement = new Movement();
                    $movement->date_shipment = $product['date'];
                    $movement->discount = $product['discount'];
                    $movement->status = 'salida';
                    $prd->movements()->save($movement);
                    $prd->status = 0;
                    $prd->save();

                };
                $response[] = $prd;
                $movementsRes[] = $movement;
            }

            return response()->json(['message' => 'Se genero la salida de los productos correctamente',
                                        'products' => $response, 'movements' => $movementsRes, 'request' => $products],200);

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

        $movement->date_shipment = $request->input('date');
        $movement->save();

        return response()->json(['message' => 'Se reprogramo la salida', 'movement' => $movement]);
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

        foreach ($products as $product) {
            $product->movement =  $product->bymovements[0];
            unset($product->bymovements);
        }

        // var_dump($products);
        // return $products;
        // dd($products->all());
        // return json_encode($products);
        return response()->json(['products' => $products], 200);
    }
    

    public function movementDay(){
        $date1 = Carbon::today();
        $date2 = $date1->copy()->addDay();
        $movements=$this->movementsGet($date1,$date2);
        return response()->json(['movements' => $movements],200);
    }
    public function movementOtherDay(Request $request){
        try {
            $date1 = Carbon::createFromFormat('Y-m-d', $request->input('date1'));            
        } catch(\InvalidArgumentException $e) {
            return response()->json(['message' => 'Fechas no validas, formato aceptado: Y-m-d'],401);
        }        
        $date2 = $date1->copy()->addDay();        
        $movements=$this->movementsGet($date1,$date2);
        return response()->json(['movements' => $movements],200);
    }

    private function movementsGet($date1,$date2){
        $status = 'salida';
        $movements=DB::table('auxproducts as p')
            ->select('m.date_shipment as fecha','p.cod as codigo','p.name as product','c.name as color',
                DB::raw('case when d.price then d.price else p.cost_provider + p.utility end as price'),'s.name as talla','m.status','m.discount',
                DB::raw('case when d.price then d.price-m.discount else p.cost_provider + p.utility -m.discount end as pricefinal'))
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->leftJoin('settlements AS d','d.product_id','=','p.id')
//            ->where('m.status','<>','%'.$status.'%')
//            ->where('m.situation',null)
            ->where('m.status','<>',$status)
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
            ->orderby('p.name','c.name','s.name')
            ->get();

        return $movements;
    }

    public function movementDays (Request $request){

        try {
            $date1 = Carbon::createFromFormat('Y-m-d', $request->input('date1'));
            $date2 = Carbon::createFromFormat('Y-m-d', $request->input('date2'));
        } catch(\InvalidArgumentException $e) {
            return response()->json(['message' => 'Fechas no validas, formato aceptado: Y-m-d'],401);
        }


        if (!\Validator::make($request->all(),['name' => 'required'])->fails()){
            $report = $this->find_for_product($date1,$date2,
                $request->input('status'),
                $request->input('name'),
                $request->input('size'),
                $request->input('color')
            );
        } else if (!\Validator::make($request->all(),['provider' => 'required'])->fails()){
            $report = $this->find_for_provider($date1,$date2,
                $request->input('status'),
                $request->input('provider')
            );
        } else {
            return response()->json([ 'message' => 'Parametros de filtro invalidos',401]);
        }

        $report['days'] = Carbon::createFromFormat('Y-m-d', substr($date1,0,10))->daysInMonth;
        return response()->json($report,200);
    }

    public function movementDaysDownload(Request $request){

        try {
            $date1 = Carbon::createFromFormat('Y-m-d', $request->input('date1'));
            $date2 = Carbon::createFromFormat('Y-m-d', $request->input('date2'));
        } catch(\InvalidArgumentException $e) {
            return response()->json(['message' => 'Fechas no validas, formato aceptado: Y-m-d'],401);
        }

//        $data = $this->entrefechas($date1,$date2,$request->input('status'),$request->input('provider'));
        if (!\Validator::make($request->all(),['name' => 'required'])->fails()){
            $data = $this->find_for_product($date1,$date2,
                $request->input('status'),
                $request->input('name'),
                $request->input('size'),
                $request->input('color')
            );
        } else if (!\Validator::make($request->all(),['provider' => 'required'])->fails()){
            $data = $this->find_for_provider($date1,$date2,
                $request->input('status'),
                $request->input('provider')
            );
        } else {
            return response()->json([ 'message' => 'Parametros de filtro invalidos',401]);
        }

//        return response()->json($data,200);
        $data = $data['movements'];
        $date = date('Y-m-d');
        $tittle = 'Reporte de movimeintos de productos entre las fechas: '.$date1->toDateString().' y '.$date2->toDateString();
        $columns = array('fecha','codigo','product','color','talla','status');

        $view =  \View::make('pdf.templatePDF', compact('data','columns','tittle','date'))->render();

        $pdf = \PDF::loadHTML($view);
        $pdf->setOrientation('landscape');

        return $pdf->download();
    }

    private function entrefechas($date1,$date2, $status = 'Vendido'){

        $movements=DB::table('auxproducts as p')
            ->select('m.date_shipment as fecha','p.cod as codigo','p.name as product','c.name as color','s.name as talla','m.status')
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('m.status','like','%'.$status.'%')
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
            ->orderby('p.name','c.name','s.name')
            ->get();

        return $movements;
    }

    private function find_for_product($date1,$date2, $status = 'Vendido',$name = '',$size = '',$color = ''){

        $data = array();
        $data['movements'] = DB::table('auxproducts as p')
            ->select('m.date_shipment as fecha','p.cod as codigo','p.name as product','c.name as color','s.name as talla','m.status')
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('m.status','like','%'.$status.'%')
            ->where('p.name','like','%'.$name.'%')
            ->where('p.size_id','like','%'.$size.'%')
            ->where('p.color_id','like','%'.$color.'%')
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
            ->orderby('m.date_shipment','asc')
            ->orderby('p.name','c.name','s.name')
            ->get();

        $data['draw'][] = $this->find_for_product_draw($date1,$status,$name,$size,$color);

        return $data;
    }

    private function find_for_product_draw($date1,$status = 'Vendido',$name = '',$size = '',$color = ''){
        $date = Carbon::createFromFormat('Y-m-d', substr($date1,0,10));

        $ini = $date->copy()->day(1)->hour(0)->minute(0)->second(0);
        $fin = $date->copy()->day($date->daysInMonth)->hour(23)->minute(59)->second(59);

        $draw = array('name' => $name);
        $draw['data'] = DB::table('auxproducts as p')
            ->select(DB::raw('DAY(m.date_shipment) as fecha'),DB::raw('count(p.cod) as quantity'))
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('m.status','like','%'.$status.'%')
            ->where('p.name','like','%'.$name.'%')
            ->where('p.size_id','like','%'.$size.'%')
            ->where('p.color_id','like','%'.$color.'%')
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$ini->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$fin->toDateString())
            ->groupby('m.date_shipment')
            ->get();

        return $draw;
    }

    private function find_for_provider($date1,$date2, $status = 'Vendido', $provider = ''){

        $data = array();
        $data['movements'] = DB::table('auxproducts as p')
            ->select('m.date_shipment as fecha','p.cod as codigo','p.name as product','c.name as color','s.name as talla','m.status')
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('m.status','like','%'.$status.'%')
            ->where('p.provider_id','=', $provider)
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
            ->orderby('m.date_shipment','asc')
            ->orderby('p.name','c.name','s.name')
            ->get();

        $data['draw'] = $this->find_for_provider_draw($date1,$status,$provider);

        return $data;
    }

    private function find_for_provider_draw($date1,$status = 'Vendido',$provider = ''){
        $date = Carbon::createFromFormat('Y-m-d', substr($date1,0,10));

        $ini = $date->copy()->day(1)->hour(0)->minute(0)->second(0);
        $fin = $date->copy()->day($date->daysInMonth)->hour(23)->minute(59)->second(59);

        $products = DB::table('auxproducts as p')
            ->select(DB::raw('DISTINCT p.name'))
            ->join('auxmovements as m','p.id','=','m.product_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('m.status','like','%'.$status.'%')
            ->where('p.provider_id','=', $provider)
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$ini->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$fin->toDateString())
            ->groupby('m.date_shipment')
            ->get();

        $draw = array();
        foreach($products as $prd){
            $data = $this->find_for_product_draw($date1,$status,$prd->name);
            $draw[] = $data;
        }

        return $draw;
    }

    public function move_day(){
        $date = Carbon::now();
        $salida = Movement::where('status','=','salida')->where('date_shipment','=',$date->toDateString())->count();
        $vendido = Movement::where('status','=','Vendido')->where('date_shipment','=',$date->toDateString())->count();
        $retornado = Movement::where('status','=','Retornado')->where('date_shipment','=',$date->toDateString())->count();
        $stock = Product::where('status',1)->count();

        return response()->json(['data' => [ 'sal' => $salida, 'ven' => $vendido, 'ret' => $retornado, 'stock' => $stock]],200);
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
            ->where(DB::raw('DATE(m.date_shipment)'),'>=',$date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'),'<',$date2->toDateString())
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
            ->get();

        return response()->json(['codes' => $product],200);
    }

}
