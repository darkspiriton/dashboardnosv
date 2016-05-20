<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Dashboard\Models\Planilla\Employee;
use Illuminate\Http\Request;
use DB;
use Dashboard\Http\Requests;

class ReportPayRollController extends Controller
{
    public function get_assists_for_month(Request $request){

        /*
         *
         * Terminos de busqueda por mes y año
         * Por defecto se devolvera registro del mes actual
         *
        $rules = [
            'year'  =>  'required|integer|between:2015,2035',
            'month' =>  'required|integer|between:1,12'
        ];

        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails())
            return response()->json(['message' => 'Terminos de busqueda erroneos'], 404);
        */

        try {
//          $date = Carbon::createFromDate($request->input('year'), $request->input('month'), 1, -5);
            $date = Carbon::now();
            $start = $date->copy()->firstOfMonth();
            $end = $date->copy()->lastOfMonth();
            $user = $request->input('user')['sub'];
            $employee = Employee::where('user_id','=',$user)->first();

            $registers = DB::table('employees as e')->select(array('a.date','a.amount',
                'a.start_time as start', 'l.start_time as break', 'l.end_time as end_break', 'a.end_time as end',
                'da.minutes as delay_min', 'da.amount as delay_amount', 'dl.minutes as lunch_min', 'dl.amount as lunch_amount'))
                ->leftJoin('assists as a', 'a.employee_id', '=', 'e.id')
                ->leftJoin('discounts_assists as da', 'da.assist_id', '=', 'a.id')
                ->leftJoin('lunches as l', 'l.date', '=', 'a.date')
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
                ->leftJoin('lunches as l', 'l.date', '=', 'a.date')
                ->leftJoin('discounts_lunches as dl', 'dl.lunch_id', '=', 'l.id')
                ->leftJoin('areas as ar', 'ar.id', '=', 'e.area_id')
                ->where('a.date', '=', $date->toDateString())
                ->get();

            return response()->json(['registers' => $registers], 200);
        }
        catch(\Exception $e){
            return response()->json([ 'message' => 'Ocurrio un problema, no podemos atender su solicitud' ], 500);
        }

    }

    public function indicator(){

    }
}
