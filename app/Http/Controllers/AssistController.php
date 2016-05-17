<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Planilla\Assist;
use Dashboard\Models\Planilla\Employee;
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
            'start_time_launch' => 'required',
            'end_time_launch' => 'required',
            'type' => 'required',
            'justification' => 'required',
        ];

        try{
            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json(['message'=>'No posee todo los campos necesarios para crear una asistencia'],401);
            }
            $type=$request->input('type');
            $justification=$request->input('justification');

            $date = Carbon::Parse($request->input('date'));
            $date->setTimezone('-5');

            $fin=$date->copy()->endOfMonth();
            $ini=$date->copy()->startOfMonth();

            $domingo=0;$lunes=0;$martes=0;$miercoles=0;$jueves=0;$viernes=0;$sabado=0;

            while($ini->diffInDays($fin) >= 0){
                $j=$ini->dayOfWeek;
                switch($j){
                    case 0:
                        $domingo++;
                        break;
                    case 1:
                        $lunes++;
                        break;
                    case 2:
                        $martes++;
                        break;
                    case 3:
                        $miercoles++;
                        break;
                    case 4:
                        $jueves++;
                        break;
                    case 5:
                        $viernes++;
                        break;
                    case 6:
                        $sabado++;
                        break;
                }
                if ($ini->diffInDays($fin)  == 0 )break;
                $ini=$ini->copy()->addDay(1);
            }

            $employe = Employee::with(['days'])
                ->where('id','=',$request->input('employe_id'))
                ->get();

            $arrays=$employe->toArray()[0]["days"];
            $sueldo=$employe->toArray()[0]["sueldo"];
            $cantD=0;$cantL=0;$cantM=0;$cantMi=0;$cantJ=0;$cantV=0;$cantS=0;
            for ($i=0;$i<count($arrays);$i++){
                $startt=Carbon::Parse($arrays[$i]["pivot"]["start_time"]);
                $endt=Carbon::Parse($arrays[$i]["pivot"]["end_time"]);

                $cant=$startt->diffInMinutes($endt)-$employe->toArray()[0]['almuerzo'];

                switch($arrays[$i]['id']){
                    case 0:
                        $cantD=$cant*$domingo;
                        break;
                    case 1:
                        $cantL=$cant*$lunes;
                        break;
                    case 2:
                        $cantM=$cant*$martes;
                        break;
                    case 3:
                        $cantMi=$cant*$miercoles;
                        break;
                    case 4:
                        $cantJ=$cant*$jueves;
                        break;
                    case 5:
                        $cantV=$cant*$viernes;
                        break;
                    case 6:
                        $cantS=$cant*$sabado;
                        break;
                }
            }
            $cantT=$cantD+$cantL+$cantM+$cantMi+$cantJ+$cantV+$cantS;
            $minuto=$sueldo/$cantT;

            //Busco al usuario para determinar la hora de trabajo de ese dia
            $employe = Employee::with(['days' => function($query) use($date)
            {
                $query->where('day_id','=', $date->dayOfWeek);

            }])->where('id','=',$request->input('employe_id'))
            ->get();

            $almuerzo=$employe->toArray()[0]['almuerzo'];
            $start = Carbon::Parse($employe->toArray()[0]["days"][0]["pivot"]["start_time"]);
            $end = Carbon::Parse($employe->toArray()[0]["days"][0]["pivot"]["end_time"]);

            $laboral=$start->diffInMinutes($end)-$almuerzo;
//            return response()->json(['message'=>$employe],200);

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
                            $this->calculo1234($start_day,$start_time_launch,$end_time_launch,$end_day,$laboral,$almuerzo,$minuto,$type,$justification);

                        }
                    }
                }elseif($request->input('end_time_launch')!=null) {
                    $end_day = Carbon::Parse($request->input('end_time_launch'));
                    $end_day->setTimezone('-5');
                    if($request->input('start_time_launch')!=null){
                        $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
                        $start_time_launch->setTimezone('-5');
                        //Calculo de horas 123
                        $this->calculo123($start_day,$start_time_launch,$end_day,$laboral,$almuerzo,$minuto,$type,$justification);

                    }
                }elseif($request->input('start_time_launch')!=null){
                    $end_day = Carbon::Parse($request->input('start_time_launch'));
                    $end_day->setTimezone('-5');
                    //Calculo de horas 12
                    $this->calculo12($start_day,$end_day,$laboral,$almuerzo,$minuto,$type,$justification);

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
                        $this->calculo234($start_day,$end_time_launch,$end_day,$laboral,$almuerzo,$minuto,$type,$justification);

                    }

                }elseif($request->input('end_time_launch')!= null){
                    $end_day = Carbon::Parse($request->input('end_time_launch'));
                    $end_day->setTimezone('-5');
                    //Calculo de horas 23
                    $this->calculo23($start_day,$end_day,$laboral,$almuerzo,$minuto,$type,$justification);

                }

            }elseif($request->input('end_time_launch')!=null){
                $start_day = Carbon::Parse($request->input('end_time_launch'));
                $start_day->setTimezone('-5');
                if($request->input('end_time')!=null){
                    $end_day = Carbon::Parse($request->input('end_time'));
                    $end_day->setTimezone('-5');
                    //Calculo de horas 34
                    $this->calculo34($start_day,$end_day,$laboral,$almuerzo,$minuto,$type,$justification);
                }
            }

//            dd($start_day->diffInMinutes($end_day));

            

            $assist = new Assist();
            $assist->employe_id=$employe->id;
            $assist->start_time=$start_day->toTimeString();
            $assist->end_time=$end_day->toTimeString();
            $assist->date=$request->input('date');
            $assist->conciliate=$request->input('type');
            $assist->justification=$request->input('justification');
            $assist->save();







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

    private function calculo1234($inicio,$inicioAlmuerzo,$finAlmuerzo,$fin,$laboral,$almuerzo,$minuto,$type,$justification){

//       dd($inicio->diffInMinutes($fin)-$almuerzo);
        //Si el valor es positivo es que falto para completar descuento por lo tanto se descuenta
        $asistencia=($laboral-($inicio->diffInMinutes($fin)-$almuerzo))*round($minuto,2);

        if($asistencia>=0){
            //si es justificado no se descuenta
            if($justification==false){
                dd($asistencia*-1);
                //registrar descuento
            }
        }else{
            //si es conciliado aumento
            if($type==true){
                dd($asistencia*-1);
                //registra aumento
            }
        }

       $almuerzo=($almuerzo-$inicioAlmuerzo->diffInMinutes($finAlmuerzo))*round($minuto,3);

        if($almuerzo<=0){
            $almuerzo;
            //se registra descuento de almuerzo
        }

    }

    private function calculo123($inicio,$inicioAlmuerzo,$fin,$laboral,$almuerzo,$minuto,$type,$justification){

    }

    private function calculo12($inicio,$inicioAlmuerzo,$laboral,$almuerzo,$minuto,$type,$justification){

    }

    private function calculo234($inicioAlmuerzo,$finAlmuerzo,$fin,$laboral,$almuerzo,$minuto,$type,$justification){

    }

    private function calculo23($inicioAlmuerzo,$finAlmuerzo,$laboral,$almuerzo,$minuto,$type,$justification){

    }

    private function calculo34($finAlmuerzo,$fin,$laboral,$almuerzo,$minuto,$type,$justification){

    }

}
