<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Publicity\Process;
use Dashboard\Models\Publicity\Publicity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dashboard\Http\Requests;
use Illuminate\Support\Facades\DB;

class PublicityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date1 = Carbon::today();
//        $publicities=Publicity::with('processes','socials')
//            ->where('date','<',$date1)
//            ->get();
        $publicities=DB::table('auxproducts as p')
            ->select('pu.date','p.name','tp.name as proceso','','')
            ->join('publicities as pu','pu.product_id','=','p.id')
            ->join('auxsocials as s','s.publicity_id','=','pu.id')
            ->join('processes as pr','pr.publicity_id','=','p.id')
            ->join('types_processes as tp','tp.id','=','pr.type_process_id')
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
            'status'    => 'required|integer',
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear una registro de publicidad'],401);
            }

            $date = Carbon::now();
            $date->timezone('America/Lima');

            $publicity= new Publicity();
            $publicity->date=$date;
            $publicity->product_id=$request->input('product_id');
            $publicity->status=$request->input('status');
            $publicity->save();

            $process= new Process();
            $process->publicity_id=$publicity->id;
            $process->date=$date;
            $process->type_process_id=1;
            $process->status=1;
            $process->save();

            return \Response::json(['message' => 'Se agrego el registro de publicidad'], 200);
        } catch (Exception $e) {
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
}
