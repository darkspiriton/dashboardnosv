<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Planilla\Assist;
use Dashboard\Models\Planilla\DiscountAssist;
use Dashboard\Models\Planilla\DiscountLunch;
use Dashboard\Models\Planilla\Employee;
use Dashboard\Models\Planilla\Extra;
use Dashboard\Models\Planilla\Lunch;
use DoctrineTest\InstantiatorTestAsset\ExceptionAsset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dashboard\Http\Requests;
use Exception;
use Illuminate\Support\Facades\DB;

class AssistController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:GOD,NOS');
    }

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
            'employee_id' => 'required',
            'start_time' => 'date',
            'end_time' => 'date',
            'date' => 'required|date',
            'start_time_launch' => 'date',
            'end_time_launch' => 'date',
            'conciliate' => 'required',
            'justification' => 'required',
        ];

        try{
            $validator = \Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json(['message'=>'No posee todo los campos necesarios para crear una asistencia'],401);
            }
            $emp= Employee::find($request->input('employee_id'));

            $date = Carbon::Parse($request->input('date'));
            $date->setTimezone("America/Lima");


            $existe=DB::table('assists')
                ->where('employee_id','=',$request->input('employee_id'))
                ->where('date','=',$date->toDateString())->exists();

            if($emp != null){
                if($existe == false){
                    $employe = Employee::with(['days'])
                        ->where('id','=',$request->input('employee_id'))
                        ->get();

                    $arrays=$employe->toArray()[0]["days"];
                    $sueldo=$employe->toArray()[0]["salary"];

                    $conciliate=$request->input('conciliate');
                    $justification=$request->input('justification');

                    //Cantidad de dias al mes laborables para calculo de costo x minuto
                    $fin=$date->copy()->endOfMonth();
                    $ini=$date->copy()->startOfMonth();

                    $minuto=$this->calculo($ini,$fin,$arrays,$employe);

                    //Busco al usuario para determinar la hora de trabajo de ese dia
                    $employe = Employee::with(['days' => function($query) use($date)
                    {
                        $query->where('day_id','=', $date->dayOfWeek);

                    }])->where('id','=',$request->input('employee_id'))
                        ->get();

                    try {
                        $employe->toArray()[0]["days"][0]["pivot"]["start_time"];
                        $almuerzo = $employe->toArray()[0]['break'];
                        $start = Carbon::Parse($employe->toArray()[0]["days"][0]["pivot"]["start_time"]);
                        $end = Carbon::Parse($employe->toArray()[0]["days"][0]["pivot"]["end_time"]);
                    } catch(Exception $e){
                        return response()->json(['message' => 'El empleado no labora este dia, no se puede guardar la asistencia'],404);
                    }


                    //Horas laborables al mes de ese empleado
                    $laboral=$start->diffInMinutes($end)-$almuerzo;

                    $employeId=$employe->toArray()[0]['id'];
                    //Funcion que procese la asistencia y realice el calculo si fuera necesario.
                    $assist = new Assist();
                    $assist->employee_id=$employeId;
                    $assist->start_time="10:00:00";
                    $assist->end_time="20:00:00";
                    $assist->date=$date;
                    $assist->conciliate=$request->input('conciliate');
                    $assist->justification=$request->input('justification');
                    $assist->save();

                    $id=$assist->id;

                    if($request->input('start_time')!=null){
                        $start_day = Carbon::Parse($request->input('start_time'));
                        $start_day->setTimezone("America/Lima");
                        if($request->input('end_time')!=null){
                            $end_day = Carbon::Parse($request->input('end_time'));
                            $end_day->setTimezone("America/Lima");
                            if($request->input('end_time_launch')!=null){
                                $end_time_launch= Carbon::Parse($request->input('end_time_launch'));
                                $end_time_launch->setTimezone("America/Lima");
                                if($request->input('start_time_launch')!=null){
                                    $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
                                    $start_time_launch->setTimezone("America/Lima");
                                    //Calculo de horas 1234
                                    return $this->asistencia($start_day,$end_day,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
                                }
                            }

                        }elseif($request->input('end_time_launch')!=null) {
                            $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
                            $end_time_launch->setTimezone("America/Lima");
                            if($request->input('start_time_launch')!=null){
                                $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
                                $start_time_launch->setTimezone("America/Lima");
                                //Calculo de horas 123
                                return $this->asistencia($start_day,$end_time_launch,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
                            }

                        }elseif($request->input('start_time_launch')!=null){
                            $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
                            $start_time_launch->setTimezone("America/Lima");
                            //Calculo de horas 12
                            return $this->asistencia($start_day,$start_time_launch,$start_time_launch,$start_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
                        }

                    }elseif($request->input('start_time_launch')!=null){
                        $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
                        $start_time_launch->setTimezone("America/Lima");
                        if($request->input('end_time')!=null){
                            $end_day = Carbon::Parse($request->input('end_time'));
                            $end_day->setTimezone("America/Lima");
                            if($request->input('end_time_launch')!=null){
                                $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
                                $end_time_launch->setTimezone("America/Lima");
                                //Calculo de horas 234
                                return $this->asistencia($start_time_launch,$end_day,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
                            }

                        }elseif($request->input('end_time_launch')!= null){
                            $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
                            $end_time_launch->setTimezone("America/Lima");
                            //Calculo de horas 23
                            return $this->asistencia($start_time_launch,$end_time_launch,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
                        }

                    }elseif($request->input('end_time_launch')!=null){
                        $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
                        $end_time_launch->setTimezone("America/Lima");
                        if($request->input('end_time')!=null){
                            $end_day = Carbon::Parse($request->input('end_time'));
                            $end_day->setTimezone("America/Lima");
                            //Calculo de horas 34
                            return $this->asistencia($end_time_launch,$end_day,$end_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
                        }
                    }
                }else{
                    return response()->json(["message" => "Ya se registro una asistencia para ese dia"],404);
                }



            }else{
                return response()->json(["message" => "El empleado no existe"],404);
            }


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
    public function show(Request $request,$id)
    {
//        $rules = [
//            'employee_id' => 'required',
//            'date' => 'required|date',
//        ];
//
//        try {
//            $validator = \Validator::make($request->all(), $rules);
//            if ($validator->fails()) {
//                return response()->json(['message' => 'No posee todo los campos necesarios para crear un empleado'],401);
//            }
//            $date = Carbon::Parse($request->input('date'));
//            $date->setTimezone("America/Lima");
//
//            $employee= Employee::with(['assists' => function($query) use ($date){
//                $query->where('date','=',$date->toDateString());
//            }],'assists.extra','assists.discount','lunch')
//            ->where('id','=',$id)
//            ->get();
//
//            return response()->json(['employee' => $employee]);
//        } catch (\Exception $e) {
//            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
//        }

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
//        $rules = [
//            'employee_id' => 'required',
//            'start_time' => 'date',
//            'end_time' => 'date',
//            'date' => 'required|date',
//            'start_time_launch' => 'date',
//            'end_time_launch' => 'date',
//            'conciliate' => 'required',
//            'justification' => 'required',
//        ];
//
//        try {
//            $validator = \Validator::make($request->all(), $rules);
//            if ($validator->fails()) {
//                return response()->json(['message' => 'No posee todo los campos necesarios para crear una asistencia'], 401);
//            }
//
//            $emp= Employee::find($request->input('employee_id'));
//
//            $date = Carbon::Parse($request->input('date'));
//            $date->setTimezone("America/Lima");
//
//
//            $existe=DB::table('assists')
//                ->where('employee_id','=',$request->input('employee_id'))
//                ->where('date','=',$date->toDateString())->exists();
//
//            if($emp != null){
//                if($existe == true){
//                    $employe = Employee::with(['days'])
//                        ->where('id','=',$request->input('employee_id'))
//                        ->get();
//
//                    $arrays=$employe->toArray()[0]["days"];
//                    $sueldo=$employe->toArray()[0]["salary"];
//
//                    $conciliate=$request->input('conciliate');
//                    $justification=$request->input('justification');
//
//                    //Cantidad de dias al mes laborables para calculo de costo x minuto
//                    $fin=$date->copy()->endOfMonth();
//                    $ini=$date->copy()->startOfMonth();
//
//                    $minuto=$this->calculo($ini,$fin,$arrays,$employe);
//
//                    //Busco al usuario para determinar la hora de trabajo de ese dia
//                    $employe = Employee::with(['days' => function($query) use($date)
//                    {
//                        $query->where('day_id','=', $date->dayOfWeek);
//
//                    }])->where('id','=',$request->input('employee_id'))
//                        ->get();
//
//                    try {
//                        $employe->toArray()[0]["days"][0]["pivot"]["start_time"];
//                        $almuerzo = $employe->toArray()[0]['break'];
//                        $start = Carbon::Parse($employe->toArray()[0]["days"][0]["pivot"]["start_time"]);
//                        $end = Carbon::Parse($employe->toArray()[0]["days"][0]["pivot"]["end_time"]);
//                    } catch(Exception $e){
//                        return response()->json(['message' => 'El empleado no labora este dia, no se puede guardar la asistencia'],404);
//                    }
//
//
//                    //Horas laborables al mes de ese empleado
//                    $laboral=$start->diffInMinutes($end)-$almuerzo;
//
//                    $employeId=$employe->toArray()[0]['id'];
//                    //Funcion que procese la asistencia y realice el calculo si fuera necesario.
//                    $assist = new Assist();
//                    $assist->employee_id=$employeId;
//                    $assist->start_time="10:00:00";
//                    $assist->end_time="20:00:00";
//                    $assist->date=$date;
//                    $assist->conciliate=$request->input('conciliate');
//                    $assist->justification=$request->input('justification');
//                    $assist->save();
//
//                    $id=$assist->id;
//
//                    if($request->input('start_time')!=null){
//                        $start_day = Carbon::Parse($request->input('start_time'));
//                        $start_day->setTimezone("America/Lima");
//                        if($request->input('end_time')!=null){
//                            $end_day = Carbon::Parse($request->input('end_time'));
//                            $end_day->setTimezone("America/Lima");
//                            if($request->input('end_time_launch')!=null){
//                                $end_time_launch= Carbon::Parse($request->input('end_time_launch'));
//                                $end_time_launch->setTimezone("America/Lima");
//                                if($request->input('start_time_launch')!=null){
//                                    $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
//                                    $start_time_launch->setTimezone("America/Lima");
//                                    //Calculo de horas 1234
//                                    return $this->asistencia($start_day,$end_day,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
//                                }
//                            }
//
//                        }elseif($request->input('end_time_launch')!=null) {
//                            $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
//                            $end_time_launch->setTimezone("America/Lima");
//                            if($request->input('start_time_launch')!=null){
//                                $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
//                                $start_time_launch->setTimezone("America/Lima");
//                                //Calculo de horas 123
//                                return $this->asistencia($start_day,$end_time_launch,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
//                            }
//
//                        }elseif($request->input('start_time_launch')!=null){
//                            $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
//                            $start_time_launch->setTimezone("America/Lima");
//                            //Calculo de horas 12
//                            return $this->asistencia($start_day,$start_time_launch,$start_time_launch,$start_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
//                        }
//
//                    }elseif($request->input('start_time_launch')!=null){
//                        $start_time_launch = Carbon::Parse($request->input('start_time_launch'));
//                        $start_time_launch->setTimezone("America/Lima");
//                        if($request->input('end_time')!=null){
//                            $end_day = Carbon::Parse($request->input('end_time'));
//                            $end_day->setTimezone("America/Lima");
//                            if($request->input('end_time_launch')!=null){
//                                $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
//                                $end_time_launch->setTimezone("America/Lima");
//                                //Calculo de horas 234
//                                return $this->asistencia($start_time_launch,$end_day,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
//                            }
//
//                        }elseif($request->input('end_time_launch')!= null){
//                            $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
//                            $end_time_launch->setTimezone("America/Lima");
//                            //Calculo de horas 23
//                            return $this->asistencia($start_time_launch,$end_time_launch,$start_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
//                        }
//
//                    }elseif($request->input('end_time_launch')!=null){
//                        $end_time_launch = Carbon::Parse($request->input('end_time_launch'));
//                        $end_time_launch->setTimezone("America/Lima");
//                        if($request->input('end_time')!=null){
//                            $end_day = Carbon::Parse($request->input('end_time'));
//                            $end_day->setTimezone("America/Lima");
//                            //Calculo de horas 34
//                            return $this->asistencia($end_time_launch,$end_day,$end_time_launch,$end_time_launch,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId);
//                        }
//                    }
//                }else{
//                    return response()->json(["message" => "Ya se registro una asistencia para ese dia"],404);
//                }
//
//
//
//            }else{
//                return response()->json(["message" => "El empleado no existe"],404);
//            }
//
//
//        }catch(Exception $e){
//            return response()->json(['message' => 'Ocurrio un error al agregar la asistencia'],500);
//        }
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

    private function asistencia($start,$end,$startA,$endA,$laboral,$almuerzo,$minuto,$conciliate,$justification,$id,$date,$employeId){

        $asistencia=(($start->diffInMinutes($end)-$almuerzo)-$laboral)*round($minuto,2);

        $asist = Assist::find($id);
        $asist->start_time=$start;
        $asist->end_time=$end;
        $asist->save();

        $lunch = new Lunch();
        $lunch->employee_id=$employeId;
        $lunch->start_time=$startA;
        $lunch->end_time=$endA;
        $lunch->date=$date;
        $lunch->save();

        if($asistencia<0){
            if($justification==false){
                $descuento = new DiscountAssist();
                $descuento->assist_id=$employeId;
                $descuento->amount=$asistencia;
                $descuento->minutes=(int)(($laboral-($start->diffInMinutes($end)-$almuerzo)));
                $asist->discount()->save($descuento);

                $asist = Assist::find($id);
                $asist->amount=($laboral+$asistencia)*round($minuto,2);
                $asist->save();
            }

        }elseif($asistencia>0){
            if($conciliate==true){
                $extra = new Extra();
                $extra->assist_id=$employeId;
                $extra->amount=$asistencia;
                $extra->minutes=(int)((($start->diffInMinutes($end)-$almuerzo)-$laboral));
                $asist->extra()->save($extra);

                $asist = Assist::find($id);
                $asist->amount=($laboral-$asistencia)*round($minuto,2);
                $asist->save();

            }
        }elseif($asistencia==0){
            $asist = Assist::find($id);
            $asist->amount=($laboral-$asistencia)*round($minuto,2);
            $asist->save();
        }

        $alm=($almuerzo-$startA->diffInMinutes($endA))*round($minuto,3);

        if($alm<0){
            $desct = new DiscountLunch();
            $desct->lunch_id=$lunch->id;
            $desct->amount=$alm;
            $desct->minutes=(int)($startA->diffInMinutes($endA)-$almuerzo);
            $lunch->discount()->save($desct);
        }

        return response()->json(['message' => 'Se registro la asistencia'],200);
    }

    public function calculo($ini,$fin,$arrays,$employe){
        $sueldo=$employe->toArray()[0]["salary"];
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

        //Se cuenta los dias que labora y cuandos minutos diarios
        $cantD=0;$cantL=0;$cantM=0;$cantMi=0;$cantJ=0;$cantV=0;$cantS=0;
        for ($i=0;$i<count($arrays);$i++){
            $startt=Carbon::Parse($arrays[$i]["pivot"]["start_time"]);
            $endt=Carbon::Parse($arrays[$i]["pivot"]["end_time"]);

            $cant=$startt->diffInMinutes($endt)-$employe->toArray()[0]['break'];

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

        //Calculo de costo por minuto mensual
        $cantT=$cantD+$cantL+$cantM+$cantMi+$cantJ+$cantV+$cantS;
        $minuto=$sueldo/$cantT;

        return $minuto;
    }


}
