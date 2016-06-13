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
        $date=Carbon::parse($request->input('date'));
        $date->timezone('America/Lima');
        $date2=$date->addDay();


        $publicities=DB::table('auxproducts as p')
            ->select('pu.id as publicity_id','p.id as product_id','pu.date','p.name','c.name as color','tp.id as type_process_id','pr.status','pr.id as process_id')
            ->join('publicities as pu','pu.product_id','=','p.id')
            ->join('auxsocials as auxs','auxs.publicity_id','=','pu.id')
            ->join('processes as pr','pr.publicity_id','=','p.id')
            ->join('types_processes as tp','tp.id','=','pr.type_process_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('pu.date','>=',$date)
            ->where('pu.date','<',$date2)
//           ->groupby('p.name','p.color_id','p.size_id')
            ->orderby('name','asc')
            ->get();

        return response()->json(['publicity',$publicities],200);
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
            $publicity->date=$date;
            $publicity->product_id=$request->input('product_id');
            $publicity->status=0;
            $publicity->save();

            $process= new Process();
            $process->publicity_id=$publicity->id;
            $process->date=$date;
            $process->type_process_id=1;
            $process->status=1;
            $process->save();

            $foto= new Process();
            $foto->publicity_id=$publicity->id;
            $foto->type_process_id=2;
            $foto->status=0;
            $foto->save();

            $envio= new Process();
            $envio->publicity_id=$publicity->id;
            $envio->type_process_id=3;
            $envio->status=0;
            $envio->save();

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
        $rules = [
            'process_old_id' => 'required|integer|exists:processes,id',
            'process_new_id' => 'required|integer|exists:processes,id',
            'type_process_id' => 'required|integer|exists:types_processes,id',
        ];

        $validator=\Validator::make($request->all(),$rules);
        if($validator->fails()){
            return reponse()->json(['message'=>'No posee todo los campos necesario para crear una registro de actualizar proceso']);
        }

        $publicity=Publicity::find($id);

        if($publicity==null){

            $processes=$publicity->processes;
            foreach ($processes as $process){

                if($request->input('type_process_id')==3){
                    $publicity->status=1;
                    $publicity->save();
                    $date_finish=Carbon::now(new DateTimeZone('America/Lima'));
                    $process->date_finish=$date_finish;
                    $process->status=0;
                    $process->save();
                }else{
                    if($process->id==$request->input('process_new_id')){
                        $date=Carbon::now(new DateTimeZone('America/Lima'));
                        $process->date=$date;
                        $process->status=1;
                        $process->save();
                    }
                    if($process->id==$request->input('process_old_id')){
                        $date_finish=Carbon::now(new DateTimeZone('America/Lima'));
                        $process->date_finish=$date_finish;
                        $process->status=0;
                        $process->save();
                    }
                }
            }
        }else{
            return response()->json(['message'=>'No se encuentra registrado ninguna publicidad con ese id']);
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
        //
    }

    public function relation($id){

        $relations=DB::table('auxproducts as p')
                    ->select('o.name')
                    ->join('products_outfits as po','po.product_id','=','p.id')
                    ->join('outfits as o','o.id','=','po.outfit_id')
                    ->where('p.id','=',$id)
                    ->where('p.status','<>',2)
                    ->get();

        $product=Product::find($id);

        $relations2=DB::table('auxproducts as p')
                    ->select('p.cod')
                    ->join('settlements as s','s.product_id','=','p.id')
                    ->where('p.name','=',$product->name)
                    ->where('p.color_id','=',$product->color_id)
                    ->where('p.size_id','=',$product->size_id)
                    ->where('p.status','<>',2)
                    ->get();

        if($relations==null){
            $outfit=false;
        }elseif(count($relations)==1){
            $outfit=$relations[0]->name;
        }else{
            $j=0;
            foreach($relations as $item){
                $outfit[$j]=$item->name;
                $j++;
            }
        }


        if($relations2==null){
            $liquidation=false;
        }elseif(count($relations2)==1){
            $liquidation=true;
        }else{
            $i=0;
            foreach ($relations2 as $item) {
                $liquidation['codigo'.$i]=$item->cod;
                $i++;
            }
        }
        return response()->json(['outfits'=>$outfit,'liquidation'=>$liquidation]);
    }

    public function indicator(Request $request){
        $date=Carbon::parse($request->input('date'));
        $date->timezone('America/Lima');
        $date2=$date->addDay();

        $cantprocesos=$this->cantidad($date,$date2,1);
        $cantfotos=$this->cantidad($date,$date2,2);
        $cantenvios=$this->cantidad($date,$date2,3);

        return response()->json(['procesos'=>$cantprocesos,'fotos'=>$cantfotos,'envios'=>$cantenvios]);

    }
    
    public function esquema()
    {

        //se colocara esquema
        $esquemas=DB::table('auxproducts as p')
            ->join('publicities as pu','pu.product_id','=','p.id')
            ->join('processes as pr','pr.publicity_id','=','pu.id')
            ->get();
        return response()->json(['message'=>$esquemas],200);
        
    }

    private function cantidad($date,$date2,$status){
        $publicities=DB::table('auxproducts as p')
            ->select(DB::raw('count(pu.id) as cant'))
            ->join('publicities as pu','pu.product_id','=','p.id')
            ->join('auxsocials as auxs','auxs.publicity_id','=','pu.id')
            ->join('processes as pr','pr.publicity_id','=','pu.id')
            ->join('types_processes as tp','tp.id','=','pr.type_process_id')
            ->join('colors as c','c.id','=','p.color_id')
            ->join('sizes as s','s.id','=','p.size_id')
            ->where('pu.date','>=',$date)
            ->where('pu.date','<',$date2)
            ->where('tp.id','=',$status)
            ->get();

        return $publicities;
    }
}
