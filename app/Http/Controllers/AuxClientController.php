<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Illuminate\Http\Request;
use Dashboard\Models\Experimental\Client;
use Validator;

class AuxClientController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:GOD,NOS,ADM,JVE");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Client::all();
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
            'name'      =>  'required|string|max:255',
            'email'     =>  'email',
            'phone'     =>  'required|string|max:25',
            'dni'       =>  'max:8',
            'address'   =>  'required|string',
            'reference' =>  'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'No se posee todos los atributos necesarios para crear un cliente'], 401);
        }

        $client = Client::where('email', request("email"))
                            ->OrWhere('phone', request("phone"))
                                ->Orwhere('dni', request("dni"))->first();

        if ($client !== null) {
            return response()->json(['message' => 'El cliente ya se encuentra registrado'], 401);
        }

        Client::create($request->all());
        return response()->json(['message' => 'El cliente se creo correctamente'], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $text
     * @return \Illuminate\Http\Response
     */
    public function show($text)
    {
        $clients = Client::where("name","like", "%".$text."%")
                            ->orWhere("email", $text)
                                ->orWhere("phone", $text)
                                    ->orWhere("dni", $text)->get();

        if ($clients == null) {
            return response()->json(['message' => 'No existe el cliente'], 401);
        }

        return $clients;
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
            'name'      =>  'required|string|max:255',
            'email'     =>  'email',
            'phone'     =>  'required|string|max:25',
            'dni'       =>  'max:8',
            'address'   =>  'required|string',
            'reference' =>  'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'No se posee todos los atributos necesarios para crear un cliente']);
        }

        $client = Client::find($id);

        if ($client == null) {
            return response()->json(['message' => 'No se encontro el cliente'], 401);
        }

        $client->update($request->all());
        return response()->json(['message' => 'Se actualizo el cliente correctamente'], 200);
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
