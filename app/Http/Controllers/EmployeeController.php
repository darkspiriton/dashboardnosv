<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Dashboard\Models\Planilla\Employee;
use Dashboard\User;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::with('days','area')->select(array('id','name','area_id','sex'))->get();

        return response()->json(['employees' => $employees],200);
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
            'area_id'   =>  'required|between:1,4',
            'name'      =>  'required|alpha',
            'sex'       =>  'required|in:M,F',
            'salary'    =>  'required|integer',
            'break'     =>  'required',
            'days'      =>  'required',
            'days.*.start_time' =>  'required|date',
            'days.*.end_time'   =>  'required|date',
            'days.*.day_id'     =>  'required|between:1,7'
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesarios para crear un empleado'],401);
            }

            $user = new User();
            $user->first_name = $request->input('name');
            $user->last_name = 'Empleado';
            $user->email = $request->input('name').'@nosvenden.com';
            $user->sex = $request->input('sex');
            $user->role_id = 6;
            $user->password = \Hash::make($request->input('name'));
            $user->status = 1;
            $user->save();

            $employee = new Employee();
            $employee->user_id = $user->id;
            $employee->area_id = $request->input('area_id');
            $employee->name = $request->input('name');
            $employee->sex = $request->input('sex');
            $employee->salary = $request->input('salary');
            $employee->break = $request->input('break');
            $employee->save();

            $days = Array();
            foreach($request->input('days') as $key => $day){
                $days[$key]['day_id'] = $day['day_id'];
                $days[$key]['start_time'] = Carbon::parse($day['start_time'])->setTimezone('-5')->toTimeString();
                $days[$key]['end_time'] = Carbon::parse($day['end_time'])->setTimezone('-5')->toTimeString();
            }
            $employee->days()->attach($days);

            return response()->json(['message' => 'El empleado se agrego correctamente'],200);
        } catch (\Exception $e) {
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
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
        try{
            $validator = \Validator::make(['id' => $id ], ['id' => 'integer']);
            if ($validator->fails()) {
                return response()->json(['message' => 'Termino de busqueda no aceptado'],401);
            }

            $employee = Employee::with('days','area')
                                ->select(array('id','name','area_id','sex','break','salary'))
                                ->where('id','=',$id)
                                ->get();

            if (count($employee) > 0 ) {
                return response()->json(['employee' => $employee[0]],200);
            }

            return \Response::json(['message' => 'No existe ese producto'], 404);

        }catch (\ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
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
        $rules = [
            'area_id'   =>  'required|between:1,4',
            'name'      =>  'required',
            'sex'       =>  'required|in:M,F',
            'salary'    =>  'required|integer',
            'break'     =>  'required',
            'days'      =>  'required',
            'days.*.start_time' =>  'required|date',
            'days.*.end_time'   =>  'required|date',
            'days.*.day_id'     =>  'required|between:1,7'
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesarios para crear un empleado'],401);
            }

            $employee = Employee::find($id);

            if($employee->exists()){
                $employee->area_id = $request->input('area_id');
                $employee->name = $request->input('name');
                $employee->sex = $request->input('sex');
                $employee->salary = $request->input('salary');
                $employee->break = $request->input('break');
                $employee->save();
                $days = Array();
                foreach($request->input('days') as $key => $day){
                    $days[$day['day_id']]['start_time'] = Carbon::parse($day['start_time'])->setTimezone('-5')->toTimeString();
                    $days[$day['day_id']]['end_time'] = Carbon::parse($day['end_time'])->setTimezone('-5')->toTimeString();
                }
                $employee->days()->sync($days);

                return response()->json(['message' => 'Se actualizaron los registros correctamente'],200);
            } else {
                return response()->json(['message' => 'Usuario no existe'],401);
            }

        } catch (\Exception $e) {
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
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
}
