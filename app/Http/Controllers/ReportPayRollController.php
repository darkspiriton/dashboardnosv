<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Dashboard\Models\Planilla\Employee;
use Illuminate\Http\Request;
use DB;
use Dashboard\Http\Requests;

class ReportPayRollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:GOD,NOS,EMP');
        $this->middleware('auth:GOD,NOS' , ['only' => ['get_payroll','get_assists_for_month_by_god']]);
    }

    public function get_assists_for_month(Request $request){

        /*
         *
         * Terminos de busqueda por mes y año
         * Por defecto se devolvera registro del mes actual
         *
         */

        $rules = [
            'year'  =>  'integer|between:2015,2035',
            'month' =>  'integer|between:1,12'
        ];

        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails())
            return response()->json(['message' => 'Terminos de busqueda erroneos'], 404);


        try {
            if ($request->input('year') && $request->input('month')){
                $date = Carbon::createFromDate($request->input('year'), $request->input('month'), 1, 'America/Lima');
            }else{
                $date = Carbon::now();
            }

            $start = $date->copy()->firstOfMonth();
            $end = $date->copy()->lastOfMonth();
            $user = $request->input('user')['sub'];
            $employee = Employee::where('user_id','=',$user)->first();

            $registers = DB::table('employees as e')->select(array('a.date','a.amount',
                'a.start_time as start', 'l.start_time as break', 'l.end_time as end_break', 'a.end_time as end',
                'da.minutes as delay_min', 'da.amount as delay_amount', 'dl.minutes as lunch_min', 'dl.amount as lunch_amount'))
                ->leftJoin('assists as a', 'a.employee_id', '=', 'e.id')
                ->leftJoin('discounts_assists as da', 'da.assist_id', '=', 'a.id')
                ->join('lunches as l', function($join){
                    $join->on('l.date', '=', 'a.date')->on('l.employee_id','=','e.id');
                })
                ->leftJoin('discounts_lunches as dl', 'dl.lunch_id', '=', 'l.id')
                ->where('e.id', '=', $employee->id)
                ->where('a.date', '>=', $start)
                ->where('a.date', '<=', $end)
                ->get();

            $discounts = Array();
            $discounts['delay_min'] = $discounts['delay_amount'] = $discounts['lunch_min'] = $discounts['lunch_amount'] =  $discounts['day_amount'] = 0;

            foreach($registers as $row){
                $discounts['day_amount']    += $row->amount;
                $discounts['delay_min']     += $row->delay_min;
                $discounts['delay_amount']  += $row->delay_amount;
                $discounts['lunch_min']     += $row->lunch_min;
                $discounts['lunch_amount']  += $row->lunch_amount;
            }

            return response()->json(['registers' => $registers, 'discounts' => $discounts], 200);
        }catch(\Exception $e){
            return response()->json([ 'message' => 'Ocurrio un problema, no podemos atender su solicitud' ], 500);
        }

    }

    public function get_assists_for_month_by_god(Request $request){

        /*
         *
         * Terminos de busqueda por mes y año
         * Por defecto se devolvera registro del mes actual
         *
         */

        $rules = [
            'id'    =>  'required|integer',
            'year'  =>  'integer|between:2015,2035',
            'month' =>  'integer|between:1,12'
        ];

        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails())
            return response()->json(['message' => 'Terminos de busqueda erroneos'], 404);


        try {
            if ($request->input('year') && $request->input('year')){
                $date = Carbon::createFromDate($request->input('year'), $request->input('month'), 1, 'America/Lima');
            }else{
                $date = Carbon::now();
            }

            $start = $date->copy()->firstOfMonth();
            $end = $date->copy()->lastOfMonth();

            $employee = Employee::find($request->input('id'));

            $registers = DB::table('employees as e')->select(array('a.date','a.amount',
                'a.start_time as start', 'l.start_time as break', 'l.end_time as end_break', 'a.end_time as end',
                'da.minutes as delay_min', 'da.amount as delay_amount', 'dl.minutes as lunch_min', 'dl.amount as lunch_amount'))
                ->leftJoin('assists as a', 'a.employee_id', '=', 'e.id')
                ->leftJoin('discounts_assists as da', 'da.assist_id', '=', 'a.id')
                ->join('lunches as l', function($join){
                    $join->on('l.date', '=', 'a.date')->on('l.employee_id','=','e.id');
                })
                ->leftJoin('discounts_lunches as dl', 'dl.lunch_id', '=', 'l.id')
                ->where('e.id', '=', $employee->id)
                ->where('a.date', '>=', $start)
                ->where('a.date', '<=', $end)
                ->get();

            $discounts = Array();
            $discounts['delay_min'] = $discounts['delay_amount'] = $discounts['lunch_min'] = $discounts['lunch_amount'] =  $discounts['day_amount'] = 0;

            foreach($registers as $row){
                $discounts['day_amount']    += $row->amount;
                $discounts['delay_min']     += $row->delay_min;
                $discounts['delay_amount']  += $row->delay_amount;
                $discounts['lunch_min']     += $row->lunch_min;
                $discounts['lunch_amount']  += $row->lunch_amount;
            }

            return response()->json(['registers' => $registers, 'discounts' => $discounts], 200);
        }catch(\Exception $e){
            return response()->json([ 'message' => 'Ocurrio un problema, no podemos atender su solicitud' ], 500);
        }

    }

    public function get_assists_by_day(Request $request){

        $rules = [
            'date'  =>  'required|date',
        ];

        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails())
            return response()->json(['message' => 'Terminos de busqueda erroneos'], 404);

        try {
            $date = Carbon::parse($request->input('date'));

            $registers = DB::table('employees as e')
                ->select(array('ar.name as area','e.name','a.date','a.start_time as start', 'l.start_time as break', 'l.end_time as end_break', 'a.end_time as end'))
                ->leftJoin('assists as a', 'a.employee_id', '=', 'e.id')
                ->leftJoin('discounts_assists as da', 'da.assist_id', '=', 'a.id')
                ->join('lunches as l', function($join){
                    $join->on('l.date', '=', 'a.date')->on('l.employee_id','=','e.id');
                })
                ->leftJoin('discounts_lunches as dl', 'dl.lunch_id', '=', 'l.id')
                ->leftJoin('areas as ar', 'ar.id', '=', 'e.area_id')
                ->where('a.date', '=', $date->toDateString())
                ->groupBy('e.name')
                ->get();

            return response()->json(['registers' => $registers], 200);
        }
        catch(\Exception $e){
            return response()->json([ 'message' => 'Ocurrio un problema, no podemos atender su solicitud' ], 500);
        }

    }

    public function get_payroll(Request $request){

        $rules = [
            'year'  =>  'integer|between:2015,2035',
            'month' =>  'integer|between:1,12',
            'employee_id' =>  'integer',
            'area_id' =>  'integer'
        ];

        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails())
            return response()->json(['message' => 'Terminos de busqueda erroneos'], 404);

        try {
            if ($request->input('year') && $request->input('year')){
                $date = Carbon::createFromDate($request->input('year'), $request->input('month'), 1, 'America/Lima');
            }else{
                $date = Carbon::now();
            }

            $start = $date->copy()->firstOfMonth();
            $end = $date->copy()->lastOfMonth();

            $payroll = Array();

            if($request->input('employee_id')){
                $employee = Employee::find($request->input('employee_id'));
                if(!$employee->exists())
                    return response()->json(['message' => 'El empleado no existe'], 404);

                $payroll[] = $this->getPayroll_for_user($employee, $start, $end);
            } else if($request->input('area_id')){
                $employees = Employee::where('area_id','=',$request->input('area_id'))->get();

                if(count($employees) == 0)
                    return response()->json(['message' => 'No hay empleados asociados al area'], 404);

                foreach($employees as $employee){
                    $payroll[] = $this->getPayroll_for_user($employee, $start, $end);
                }
            } else {
                $employees = Employee::all();

                if(count($employees) == 0)
                    return response()->json(['message' => 'No hay empleados para listar'], 404);

                foreach($employees as $employee){
                    $payroll[] = $this->getPayroll_for_user($employee, $start, $end);
                }
            }

            return response()->json(['payroll' => $payroll], 200);

        }catch(\Exception $e){
            return response()->json([ 'message' => 'Ocurrio un problema, no podemos atender su solicitud' ], 500);
        }
    }

    private function getPayroll_for_user(Employee $employee, $start, $end){
        $registers = DB::table('assists as a')->select(array('a.amount',
            'da.minutes as delay_min', 'da.amount as delay_amount', 'dl.minutes as lunch_min', 'dl.amount as lunch_amount'))
            ->leftJoin('discounts_assists as da', 'da.assist_id', '=', 'a.id')
            ->leftJoin('lunches as l', 'l.date', '=', 'a.date')
            ->leftJoin('discounts_lunches as dl', 'dl.lunch_id', '=', 'l.id')
            ->where('a.employee_id', '=', $employee->id)
            ->where('a.date', '>=', $start)
            ->where('a.date', '<=', $end)
            ->get();

        $amounts = Array();
        $amounts['delay_min'] = $amounts['delay_amount'] = $amounts['lunch_min'] = $amounts['lunch_amount'] =  $amounts['pay_amount'] = 0;

        $amounts['id'] = $employee->id;
        $amounts['name'] = $employee->name;
        $amounts['salary'] = $employee->salary;
        foreach($registers as $row){
            $amounts['pay_amount']    += $row->amount;
            $amounts['delay_min']     += $row->delay_min;
            $amounts['delay_amount']  += $row->delay_amount;
            $amounts['lunch_min']     += $row->lunch_min;
            $amounts['lunch_amount']  += $row->lunch_amount;
        }

        return $amounts;
    }
}
