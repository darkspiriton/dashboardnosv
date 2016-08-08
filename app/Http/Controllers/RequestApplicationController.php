<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Dashboard\Http\Requests\RequestApplicationRequest;
use Dashboard\Models\Request\User;
use Illuminate\Http\Request;

class RequestApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:GOD,ADM,JVE", ["except" => ["store"]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::where("status", 0)->get();
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
    public function store(RequestApplicationRequest $request)
    {
        User::create($request->all());

        return response()->json(["message" => "Se registro el formulario de contacto, nos estaremos comunicando con usted a la brevedad."]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::where("status", $id)->get();
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
        $this->validate($request, ["status" => "required|integer|between:1,2"]);

        $user = User::findOrFail($id);

        if ($user->status == 0) {
            $user->status = request("status");
            $user->save();

            return response()->json(["message" => "Se cambio estado de formulario de contacto."]);
        }

        return response()->json(["message" => "El formulario de contacto ya tiene un estado no puede ser modificado."], 422);
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

    /**
     *    Retorno de estado de busqueda.
     *
     *    @param  Illuminate\Http\Request  $request
     *    @return  Illuminate\Http\Response
     */
    public function status()
    {
        $status = [
            ["id" => 0, "name" => "Sin atender"],
            ["id" => 1, "name" => "Atendido"],
            ["id" => 2, "name" => "Rechazado"],
        ];

        return $status;
    }
}
