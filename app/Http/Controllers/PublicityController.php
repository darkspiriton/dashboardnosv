<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Publicity\Publicity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dashboard\Http\Requests;

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
        $publicities=Publicity::with('processes','socials')
            ->where('date','<',$date1)
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
            'product_id'=> 'required|integer|exists:auxproducts.id',
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

            $product = DB::table('auxproducts')
                ->where('cod','=',$request->input('cod'))
                ->get();

            if($product == null){
                
            } else {
                return response()->json(['message' => 'El código del registro de publicidad ya existe'],401);
            }



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
