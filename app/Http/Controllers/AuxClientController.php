<?php

namespace Dashboard\Http\Controllers;

use Validator;
use Carbon\Carbon;
use Dashboard\User;
use Dashboard\Http\Requests;
use Illuminate\Http\Request;
use Dashboard\Events\NotificationPusher;
use Dashboard\Models\Experimental\Client;
use Dashboard\Models\Experimental\Movement;

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
        return response()->json(['clients'=>Client::all()],200);
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
        'dni'       =>  'min:8,max:8',
        'address'   =>  'required|string',
        'reference' =>  'required|string',
        'facebook_id' =>  'integer',
        'facebook_name' =>  'string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'No se posee todos los atributos necesarios para crear un cliente'], 403);
        }
        if( request("facebook_id")!=null && request("email")!=null && request("dni")!=null){
            $client = Client::where('phone', request("phone"))
                ->orWhere("facebook_id", request("facebook_id"))
                ->orWhere("email", request("email"))
                ->orWhere("dni", request("dni"))->first();
        }else{
            $client = Client::where('phone', request("phone"))->first();
        }

        if ($client !== null) {
            return response()->json(['message' => 'El cliente ya se encuentra registrado'], 401);
        }

        $responseClient=Client::create($request->all());
        return response()->json(['message' => 'El cliente se creo correctamente', "client" => $responseClient], 200);
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
        ->orWhere("facebook_name",$text)
        ->orWhere("facebook_id",$text)
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
        'reference' =>  'required|string',
        'facebook_id' =>  'integer',
        'facebook_name' =>  'string',

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'No se posee todos los atributos necesarios para crear un cliente'],401);
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
        $client = Client::find($id);

        if ($client == null){
            return response()->json(['message' => 'No se encontro el cliente a eliminar'],401);
        }


        //eliminar cliente
        $client->delete();

        //notificar eliminacion de archivo

        // $user_id = request("user")["sub"];
        // $user = User::find($user_id);
        // $body = "Usuario: ".$user->first_name
        // ." Elimino el codigo: ".$client->id
        // .", nombre: ".$client->name
        // .", email: ".$client->email
        // .", phone: ".$client->phone;

        // event(new NotificationPusher("Eliminacion de cliente", $body, 2, "clientDelete"));
        return response()->json(['message'=>'Se elimino el cliente correctamente'],200);
    }

    public function restore($id)
    {
        $client = Client::withTrashed()->find($id);

        if ($client == null) {
            return response()->json(["message" => "El cliente a restaurar no existe."]);
        }

        if ($client->trashed()) {
            $client->restore();


            $user_id = request("user")["sub"];
            $user = User::find($user_id);

            // $body = "Usuario: ".$user->first_name
            //             ." Restauro el codigo: ".$client->id
            //                 .", nombre: ".$client->name;

            // event(new NotificationPusher("Restauracion de producto", $body, 4, "productRestore"));

            return response()->json(["message" => "El cliente se restauro con exito."]);
        } else {
            return response()->json(["message" => "El cliente no se encuentra eliminado."]);
        }
    }

    public function FilterForSoftDelete(){
        $client = Client::onlyTrashed()->get();
        if($client==null){
            return response()->json(['message' => 'No hay productos eliminados'],401);
        }
        return response()->json([ 'clients' => $client ],200);
    }

    public function getMovement(Request $request,$id){
        $rules = [
            'status'=>'required|string',
            'year' => 'integer',
            'month' => 'integer'
        ];
        $validate = Validator::make($request->all(),$rules);
        if($validate->fails()){
            return response()->json(['message' => 'No posee todos los parametros para la busqueda'],404);
        }     
        $status = $request->input('status');
        
        if ($request->has('year') && $request->has('month') && $request->input('status')!="Todo"){
            $dateIni=Carbon::create($request->input('year'), $request->input('month'), 1,0,0,0);
            $dateFin=$dateIni->copy()->addMonth();

            $client = Client::with(['movements' => function($query) use ($status,$dateIni,$dateFin){
                $query->where('status',$status)
                    ->where('date_shipment','>=',$dateIni)
                    ->where('date_shipment','<',$dateFin);
            },'movements.product','movements.product.size','movements.product.color'])
                ->where('id',$id)->get();
        } else if ($request->input('status') != 'Todo'){
            $client = Client::with(['movements' => function($query) use ($status){
                $query->where('status',$status);
            },'movements.product','movements.product.size','movements.product.color'])
                ->where('id',$id)->get();
        } else if ($request->input('status') == 'Todo'){
            $client = Client::with('movements','movements.product','movements.product.size','movements.product.color')
                ->where('id',$id)->get();
        }

        foreach ($client[0]->movements as $movement) {
            $movement->total_price=$movement->product->final_price-$movement->discount;
        };       
        
        return response()->json(['movements'=>$client[0]->movements],200);
    }

    public function countMovement(){        
        $movements = Movement::where('status','Vendido')
                ->where('date_shipment','>=','2016-08-01')
                ->where('date_shipment','<','2016-09-01')
                ->orderby('date_shipment','asc')
                ->get();

        
        foreach ($movements as $movement) {            
            $data[$movement->date_shipment]= $data[$movement->date_shipment] + 1;
        }        

        return $data;
    }

}
