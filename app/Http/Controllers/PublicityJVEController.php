<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Dashboard\Models\Publicity\Process;
use Dashboard\Models\Publicity\Publicity;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class PublicityJVEController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publicities = Publicity::with(['process' => function($query){
            return $query->with('type')->orderBy('id','desc');
        },'product' => function($query){
            return $query->with('color','provider');
        },'socials' => function($query){
            $query->with('type');
        }])->get();

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'opc' => 'required|integer|between:0,1',
            'id'  => 'required|integer|exists:publicities'
        ];

        if(\Validator::make($request->all(), $rules)->fails())
            return response()->json(['message' => 'Parametro recibidos no validos'], 401);

        $publicity = Publicity::with(['process' => function($query){
            return $query->with('type')->orderBy('id','desc')->take(1);
        }])->where('id','=',$request->input('id'))->first();

        if($publicity->process->type_process_id != 3)
            return response(['message' => 'No se puedo determinar el estado de la publicidad'],401);

        $message = '';
        if($request->input('opc') == 0){
            $publicity->status = 0;
            $message = "La imagen fue rechazada";
        } else if($request->input('opc') == 1){
            $publicity->status = 1;
            $publicity->date_finish = Carbon::now()->toDateTimeString();
            $message = "La imagen fue aprovada";
        }

        $publicity->process->status = 1;
        $publicity->push();

        return response()->json(['message' => $message], 200);
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
