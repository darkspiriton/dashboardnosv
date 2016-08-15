<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Illuminate\Http\Request;
use Dashboard\Models\Experimental\Client;

class AuxClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();
        if($clients->IsEmpty()){
            return response()->json(['message' => 'No hay usuario registrado'],401);
        }else{  
            return response()->json(['clients' => $clients],200);    
        }
        
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'dni' => 'required|string',
            'address' => 'required|string',
            'reference' => 'required|string',            
        ];

        $validator = \Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['message' => 'No se posee todos los atributos necesarios para crear un cliente']);
        }

        $name=strtolower($request->input('name'));
        $email=strtolower($request->input('email'));
        $phone=strtolower($request->input('phone'));
        $dni=strtolower($request->input('dni'));
        $address=strtolower($request->input('address'));
        $reference=strtolower($request->input('reference'));

        $client = Client::where('name',$name)
                    ->where('email',$email)
                    ->where('phone',$phone)
                    ->where('dni',$dni)->get();

        if($client !== null){
            return response()->json(['message' => 'El cliente ya se encuentra registrado'],401);
        }else{
            $clientAux= new Client();
            $clientAux->name=$name;
            $clientAux->email=$email;
            $clientAux->phone=$phone;
            $clientAux->dni=$dni;
            $clientAux->address=$address;
            $clientAux->reference=$reference;
            $clientAux->save();

            return response()->json(['message'=>'El cliente se creo correctamente'],200);
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
        $client = Client::find($id);

        if($client !== null){
            return response()->json(['client' => $client],200);
        }else{
            return response()->json(['message' => 'No existe el cliente'],401);
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'dni' => 'required|string',
            'address' => 'required|string',
            'reference' => 'required|string',            
        ];

        $validator = \Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['message' => 'No se posee todos los atributos necesarios para crear un cliente']);
        }

        $name=strtolower($request->input('name'));
        $email=strtolower($request->input('email'));
        $phone=strtolower($request->input('phone'));
        $dni=strtolower($request->input('dni'));
        $address=strtolower($request->input('address'));
        $reference=strtolower($request->input('reference'));

        $client = Client::find($id);

        if($client !== null){
            $client->name=$name;
            $client->email=$email;
            $client->phone=$phone;
            $client->dni=$dni;
            $client->address=$address;
            $client->reference=$reference;
            $client->save();

            return response()->json(['message' => 'Se actualizo el cliente correctamente'],200);

        }else{

            return response()->json(['message' => 'No se encontro el cliente'],401);            
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
        
    }
}
