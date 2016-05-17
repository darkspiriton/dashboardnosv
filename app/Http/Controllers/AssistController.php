<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Planilla\Assist;
use Dashboard\Models\Planilla\Employe;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Dashboard\Http\Requests;
use Mockery\CountValidator\Exception;

class AssistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'employe_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'date' => 'required',
//            'start_time_launch' => 'required',
//            'end_time_launch' => 'required',
//            'type' => 'required',
//            'justification' => 'required',
        ];

        try{
            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json(['message'=>'No posee todo los campos necesarios para crear una asistencia'],401);
            }
            $date = Carbon::Parse($request->input('date'));
            $date->setTimezone('-5');
            dd($date->dayOfWeek);

            $employe = Employe::find($request->input('employe_id'));
            $emplote = 
            
            



            if($request->input('start_time')!=null){
                $start_day = Carbon::Parse($request->input('start_time'));
                $start_day->setTimezone('-5');
                if($request->input('end_time')!=null){
                    $end_day = Carbon::Parse($request->input('end_time'));
                    $end_day->setTimezone('-5');
                    if($request->input('end_time_launch')!=null){
                        $end_time_launch= Carbon::Parse($request->input('end_time_launch'));
                        $end_time_launch->setTimezone('-5');
                        if($request->input('start_time_launch')!=null){
                            $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
                            $start_time_launch->setTimezone('-5');
                            //Calculo de horas 1234
                            $this->calculo1234($start_day,$start_time_launch,$end_time_launch,$end_day);
                        }
                    }
                }elseif($request->input('end_time_launch')!=null) {
                    $end_day = Carbon::Parse($request->input('end_time_launch'));
                    $end_day->setTimezone('-5');
                    if($request->input('start_time_launch')!=null){
                        $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
                        $start_time_launch->setTimezone('-5');
                        //Calculo de horas 123
                        $this->calculo123($start_day,$start_time_launch,$end_day);
                    }
                }elseif($request->input('start_time_launch')!=null){
                    $end_day = Carbon::Parse($request->input('start_time_launch'));
                    $end_day->setTimezone('-5');
                    //Calculo de horas 12
                    $this->calculo12($start_day,$end_day);
                }

            }elseif($request->input('start_time_launch')!=null){
                $start_day = Carbon::Parse($request->input('start_time_launch'));
                $start_day->setTimezone('-5');
                if($request->input('end_time')!=null){
                    $end_day = Carbon::Parse($request->input('end_time'));
                    $end_day->setTimezone('-5');
                    if($request->input('end_time_launch')!=null){
                        $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
                        $end_time_launch->setTimezone('-5');
                        //Calculo de horas 234
                        $this->calculo234($start_day,$end_time_launch,$end_day);

                    }

                }elseif($request->input('end_time_launch')!= null){
                    $end_day = Carbon::Parse($request->input('end_time_launch'));
                    $end_day->setTimezone('-5');
                    //Calculo de horas 23
                    $this->calculo23($start_day,$end_day);
                }

            }elseif($request->input('end_time_launch')!=null){
                $start_day = Carbon::Parse($request->input('end_time_launch'));
                $start_day->setTimezone('-5');
                if($request->input('end_time')!=null){
                    $end_day = Carbon::Parse($request->input('end_time'));
                    $end_day->setTimezone('-5');
                    //Calculo de horas 34
                    $this->calculo34($start_day,$end_day);
                }

            }

            dd($start_day->diffInMinutes($end_day));

            

            $assist = new Assist();
            $assist->employe_id=$employe->id;
            $assist->start_time=$start_day->toTimeString();
            $assist->end_time=$end_day->toTimeString();
//            $assist->type=$request->input('type');
//            $assist->type=$request->input('justification');
            $assist->save();



            dd($assist->created_at);



            //Funcion que procese la asistencia y realice el calculo si fuera necesario.


        }catch(Exception $e){
            return response()->json(['message' => 'Ocurrio un error al agregar la asistencia'],500);
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

    private function calculo1234($inicio,$inicioAlmuerzo,$finAlmuerzo,$fin){

    }

    private function calculo123($inicio,$inicioAlmuerzo,$fin){

    }

    private function calculo12($inicio,$inicioAlmuerzo){

    }

    private function calculo234($fin,$inicioAlmuerzo,$finAlmuerzo){

    }

    private function calculo23($inicioAlmuerzo,$finAlmuerzo){

    }

    private function calculo34($finAlmuerzo,$fin){

    }
}
