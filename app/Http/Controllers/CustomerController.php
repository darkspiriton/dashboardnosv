<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Customer\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dashboard\Http\Requests;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json(['customers' => $customers],200);
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
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'name'      => 'required',
            'age'      => 'required',
            'status'     => 'required',
            //falta validar los atributos
        ];

        //Se va a pasar datos del producto, attributos y su cantidad

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un cliente'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $customer=DB::table('customers')->where('email',$request->input('email'))->get();

            if(count($customer) == 0 ){
                $customer = new Customer();
                $customer->name= $request->input('name');
                $customer->age= $request->input('age');
                $customer->status= $request->input('status');
                $customer->user_id= $request->input('user_id');
                $customer->save();

//              $address = $request->input('address');
//              $customer->addresses($address);
//
//              $phone = $request->input('address');
//              $customer->addresses($phone);
//
//              $social = $request->input('address');
//              $customer->addresses($social);

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'El cliente se agrego correctamente'],200);
            }

            return response()->json(['message' => 'El cliente ya esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar cliente'], 500);
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
            $customer = Customer::find($id);

            if ($customer !== null) {

                $addresses = $customer->addresses;
                $phones = $customer->phones;
                $socials = $customer->socials;

                foreach ($addresses as $address){
                    $address->ubigeo;
                }
                foreach ($phones as $phone){
                    $phone->operator;
                }
                foreach ($socials as $social){
                    $social->channel;
                }
                return response()->json([
                    'message' => 'Mostrar detalles de cliente',
                    'customer'=> $customer,
                    //'attributes' => $product->attributes,
                ],200);
            }
            return \Response::json(['message' => 'No existe ese cliente'], 404);

        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
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
        try{
            $customer = Customer::find($id);
            if ($customer->status == 1){
                $customer->status = 0 ;
                $customer->save();
                return response()->json(['message' => 'Se desactivo correctamente el cliente'],200);
            }elseif ($customer->status == 0){
                $customer->status = 1;
                $customer->save();
                return response()->json(['message' => 'Se activo correctamente el cliente'],200);
            }
            return \Response::json(['message' => 'No existe el cliente'], 404);
        }catch (ErrorException $e){
            return \Response::json(['message' => 'Ocurrio un error'], 500);
        }
    }
}
