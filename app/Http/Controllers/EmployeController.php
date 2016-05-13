<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Planilla\Employe;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employe::all();

        return response()->json(['employees'=>$employees],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'area_id'          => 'required',
            'name'         => 'required',
            'sex'           => 'required',
            'sueldo'     => 'required',
            'almuerzo'        => 'required',
        ];


        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un producto'],401);
            }

            $employe= new Employe();
            $employe->area_id=$request->input('area_id');
            $employe->name=$request->input('name');
            $employe->sex=$request->input('sex');
            $employe->sueldo=$request->input('sueldo');
            $employe->almuerzo=$request->input('almuerzo');
            $request->save();

            $horarios= $request->input('horarios');

            foreach ($horarios as $horario){
                $employe->id;
                $employe->days()->attach($request->input('horarios'));
            }
            

            return response()->json(['message' => 'El producto se agrego correctamente'],200);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
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
            $employe = Employe::find($id);
            if ($employe !== null) {
                $employe->movements;
                $employe->provider;
                return response()->json([
//                    'message' => 'Mostrar detalles de producto',
//                    'product'=> $product,
                    //'attributes' => $product->attributes,
                ],200);
            }
            return \Response::json(['message' => 'No existe ese producto'], 404);

        }catch (ErrorException $e){
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
