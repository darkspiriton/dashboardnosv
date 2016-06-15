<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Product;
use Dashboard\Models\Publicity\Process;
use Dashboard\Models\Publicity\Publicity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dashboard\Http\Requests;
use Illuminate\Support\Facades\DB;
use DateTimeZone;

class PublicityController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:JVE');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('date')) {
            $date = Carbon::parse($request->input('date'));
        } else {
            $date = Carbon::now()->hour(0)->minute(0)->second(0);
        }

        $date->timezone('America/Lima');
        $date2 = $date->copy()->addDay();

        $publicities = Publicity::with(['process' => function($query){
            return $query->with('type')->orderBy('id','desc');
        },'product' => function($query){
            return $query->with('color','provider');
        },'socials' => function($query){
            $query->with('type');
        }])->where('date','>=',$date->toDateTimeString())
            ->where('date','<',$date2->toDateTimeString())
            ->get();

        $socials = array();
        foreach ($publicities as $publicity){
            $socials = array();
            foreach ($publicity->socials as $social){
                $socials[] = $social->type->name;
            }
            $publicity->socials_list = implode(' ', $socials);

            if($publicity->photo)
                $publicity->photo = url('/img/publicities/'.$publicity->photo);
            else
                $publicity->photo = url('/img/publicities/default.png');
        }

        return response()->json(['publicities' => $publicities],200);
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
            'product_id'=> 'required|integer',
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear una registro de publicidad'],401);
            }

            $date = Carbon::now(new DateTimeZone('America/Lima'));

            $publicity= new Publicity();
            $publicity->date=$date->toDateTimeString();
            $publicity->product_id=$request->input('product_id');
            $publicity->status=0;
            $publicity->save();

            $process= new Process();
            $process->publicity_id=$publicity->id;
            $process->date=$date;
            $process->type_process_id=1;
            $process->status=0;
            $process->save();

            return \Response::json(['message' => 'Se agrego el registro de publicidad'], 200);
        } catch (\Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar un registro de publicidad'], 500);
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
        $publicity = Publicity::find($id);

        if($publicity->photo)
            $publicity->photo = url('/img/publicities/'.$publicity->photo);
        else
            $publicity->photo = url('/img/publicities/default.png');

        return response()->json(['publicity' => $publicity],200);
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
        $publicity = Publicity::find($id);

        if(is_null($publicity))
            return response()->json(['message' => 'La publicidad no existe'], 404);

        $process = Process::where('publicity_id','=',$publicity->id)->orderBy('id','desc')->first();

        if($process->type_process_id > 2)
            return response()->json(['message' => 'Acción no autorizada'], 401);

        $process->status = 1;

        $publicity_process = new Process();
        $publicity_process->publicity_id = $publicity->id;
        $publicity_process->date = Carbon::now()->toDateTimeString();
        $publicity_process->type_process_id = $process->type_process_id +1;
        $publicity_process->status = 0;

        $process->save();
        $publicity_process->save();

        return response()->json(['message' => 'Se registro avance de publicidad'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function upload_image(Request $request, $id){
        $publicity = Publicity::find($id);

        if(is_null($publicity))
            return response()->json(['message' => 'La publicidad no existe'], 404);

        if($publicity->status == 1)
            return response()->json(['message' => 'La imagen ya fue aprobada no puede ser modificada'], 401);

        if($request->hasFile('img')){
            $image = $request->file('img'); // referencia a la imagen
            $image_name = str_random(4).'_'.$image->getClientOriginalName();
            $image_folder = 'img/publicities/';
            $image->move(public_path($image_folder), $image_name); // moviendo imagen a images folder

            $publicity->photo = $image_name;
            $publicity->save();

            return response()->json(['message' => 'Se guardo la imagen'], 200);
        } else {
            return response()->json(['message' => 'No se recibio ninguna imagen'], 401);
        }
    }

    public function relation($id){

        $relations = DB::table('auxproducts as p')
                        ->select('o.name')
                        ->join('products_outfits as po','po.product_id','=','p.id')
                        ->join('outfits as o','o.id','=','po.outfit_id')
                        ->where('p.id','=',$id)
                        ->where('p.status','<>',2)
                        ->lists('name');

        $product = Product::find($id);

        $relations2 = DB::table('auxproducts as p')
                        ->select('p.cod')
                        ->join('settlements as s','s.product_id','=','p.id')
                        ->where('p.name','=',$product->name)
                        ->where('p.color_id','=',$product->color_id)
                        ->where('p.size_id','=',$product->size_id)
                        ->where('p.status','<>',2)
                        ->lists('cod');

        $outfit = $liquidation = '';
        if ( $relations == null){
            $outfit = 'No';
        } else {
            $outfit = implode(' | ',$relations);
        }

        if($relations2 == null){
            $liquidation = 'No';
        } else {
            $liquidation = 'Si';
            $liquidation .= ' - Codigos: '.implode(' | ',$relations2);
        }

        return response()->json(['outfits'=>$outfit,'liquidation'=>$liquidation]);
    }

    public function indicator(Request $request){
        $date=Carbon::parse($request->input('date'));
        $date->timezone('America/Lima');
        $date2=$date->addDay();

        $cantprocesos=$this->cantidad($date,$date2);


        return response()->json(['procesos'=>$cantprocesos]);

    }
    
    public function esquema()
    {
        $date=Carbon::now(new DateTimeZone('America/Lima'));
        $date2=$date->addDay();

        //se colocara esquema
        $esquemas=DB::table('auxproducts as p')
            ->select('pu.id as pu','pr.id','pr.type_process_id','pr.date','pr.date_finish','pr.status','pu.status as aprobado')
            ->join('publicities as pu','pu.product_id','=','p.id')
            ->join('processes as pr','pr.publicity_id','=','pu.id')
//            ->where('pu.date','>=',$date)
//            ->where('pu.date','<',$date2)
            ->get();
        if($esquemas == null){
            return response()->json(['message'=>'No hay registros de publicidad hasta el momento'],404);
        }
        return response()->json(['esquemas'=>$esquemas],200);
    }

    private function cantidad($date,$date2){
        $publicities=DB::table('auxproducts as p')
            ->select('tp.name',DB::raw('count(pu.id) as cant'))
            ->leftjoin('publicities as pu','pu.product_id','=','p.id')
            ->join('auxsocials as auxs','auxs.publicity_id','=','pu.id')
            ->leftjoin('processes as pr','pr.publicity_id','=','pu.id')
            ->leftjoin('types_processes as tp','tp.id','=','pr.type_process_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('pu.date','>=',$date)
            ->where('pu.date','<',$date2)
            ->groupby('tp.id')
            ->where('pu.status','=',0)
            ->get();

        $complete=DB::table('auxproducts as p')
            ->select(DB::raw('count(pu.id) as cant'))
            ->leftjoin('publicities as pu','pu.product_id','=','p.id')
            ->where('pu.date','>=',$date)
            ->where('pu.date','<',$date2)
            ->where('pu.status','=',1)
            ->get();


        if ($publicities==null){
            $cant=0;
            for($i=0;$i<4;$i++){
                switch ($i){
                    case 0:
                        $name="Proceso";
                        break;
                    case 1:
                        $name="Foto";
                        break;
                    case 2:
                        $name="Envio";
                        break;
                    case 3:
                        $name="Completado";
                        $cant=$complete[0]->cant;
                        break;
                }
                $publicities[$i]=["name"=>$name,"cant"=>$cant];
            }
            return $publicities;
        }

        $publicities[3]=["name"=>"Completado","cant"=>$complete[0]->cant];
        return $publicities;
    }
}
