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
                $publicity->photo = url('/img/publicities/default.jpg');
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
            $publicity->date_finish="0000-00-00 00:00:00";
            $publicity->product_id=$request->input('product_id');
            $publicity->status=0;
            $publicity->save();

            $process= new Process();
            $process->publicity_id=$publicity->id;
            $process->date=$date->toDateTimeString();
            $process->date_finish="0000-00-00 00:00:00";
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
            $publicity->photo = url('/img/publicities/default.jpg');

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

        $date_finish=Carbon::now(new DateTimeZone('America/Lima'));

        $process->status = 1;
        $process->date_finish=$date_finish->copy()->toDateTimeString();

        $id = $process->type_process_id;
        $publicity_process = new Process();
        $publicity_process->publicity_id = $publicity->id;
        $publicity_process->date = $date_finish->toDateTimeString();
        $publicity_process->date_finish="0000-00-00 00:00:00";
        $publicity_process->type_process_id = $id +1;
        $publicity_process->status = 0;

//        return response()->json(['process' => $process, 'publi' => $publicity_process],200);
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
        try{
            $publicity = Publicity::with(['process' => function($query){
                return $query->with('type')->orderBy('id','desc');
            }])->find($id);

            if(is_null($publicity))
                return response()->json(['message' => 'La publicidad no existe'], 404);

            if($publicity->status == 1)
                return response()->json(['message' => 'La imagen ya fue aprobada no puede ser modificada'], 401);

            if($request->hasFile('img')){
                $image = $request->file('img'); // referencia a la imagen
                $image_name = str_random(4).'_'.$image->getClientOriginalName();
                $image_folder = 'img/publicities/';
                $image->move(public_path($image_folder), $image_name); // moviendo imagen a images folder

                if($publicity->process->type_process_id == 3)
                    $publicity->process->status = 0;

                $publicity->photo = $image_name;
                $publicity->save();
                $publicity->process->save();

                return response()->json(['message' => 'Se guardo la imagen'], 200);
            } else {
                return response()->json(['message' => 'No se recibio ninguna imagen'], 401);
            }
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()],500);
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
        $date=Carbon::today(new DateTimeZone('America/Lima'));
        $esquemas=$this->getEsquemas($date);
        if($esquemas == null){
            return response()->json(['message'=>'No hay registros de publicidad'],404);
        }
        $this->formatEsquemas($esquemas);
        return response()->json(['esquemas'=>$esquemas],200);
    }

    public function esquemaDate(Request $request)
    {
        $date=Carbon::parse($request->input('date1'));
        $date->setTimezone('America/Lima');
        $esquemas=$this->getEsquemas($date);
        if($esquemas == null){
            return response()->json(['message'=>'No hay registros de publicidad'],404);
        }
        $this->formatEsquemas($esquemas);
        return response()->json(['esquemas'=>$esquemas],200);
    }

    private function getEsquemas($date){
        $date2=$date->copy()->addDay();

        $esquemas=DB::table('auxproducts as p')
            ->select('p.name','pu.id as pu','pr.type_process_id as type','pr.date','pr.date_finish','pr.status','pu.status as aprobado')
            ->join('publicities as pu','pu.product_id','=','p.id')
            ->join('processes as pr','pr.publicity_id','=','pu.id')
//            ->where('pr.type_process_id',3)
//            ->where('pu.status',1)
            ->where('pu.date','>=',$date->toDateTimeString())
            ->where('pu.date','<',$date2->toDateTimeString())
            ->orderby('pu.date','desc')
            ->get();

        return $esquemas;
    }

    private function formatEsquemas($esquemas){
        foreach ($esquemas as $esquema){
            $date1=Carbon::parse($esquema->date);
            $date2=Carbon::parse($esquema->date_finish);
            if($date1>$date2){
                $esquema->date=$date1->toTimeString();
                $esquema->date_finish="En Proceso";
                $esquema->diff="En Proceso";
            }else{
                $min=$date1->diffInMinutes($date2);
                $esquema->diff=$min;
                $esquema->date=$date1->toTimeString();
                $esquema->date_finish=$date2->toTimeString();
            }
            
        }
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

    public function ByFacebook(Request $request){
        $publicities = Publicity::with(['product' => function($query){
            return $query->with('color','provider');
        }])->where('facebookID','<>',null)->get();

        $ids = $publicities->implode('facebookID',',');

        foreach ($publicities as $publicity){
            $publicity->sales = Product::where('name','=', $publicity->product->name)
                ->where('color_id','=',$publicity->product->color_id)
                ->where('size_id','=',$publicity->product->size_id)
                ->where('status','=',2)->count();
        }

        return response()->json(['publicities' => $publicities, 'ids' => $ids],200);
    }
}
