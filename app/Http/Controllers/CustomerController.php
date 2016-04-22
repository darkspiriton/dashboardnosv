<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Customer\Address;
use Dashboard\Models\Customer\Customer;
use Dashboard\Models\Customer\Social;
use Illuminate\Http\Request;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=$request->input('user');
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'name'      => 'required',
            'age'      => 'required',
            'status'     => 'required',
            'phone'     => 'required',
            //falta validar los atributos
        ];


        try {
            $customer= DB::table('customers')
                ->where('phone',$request->input('phone'))
                ->get();

            if($customer != null){

                // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
                // con los errores
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['message' => 'No posee todo los campos necesario para crear un cliente'],401);
                }
                $name = $request->input('name');
                $customer = new Customer();
                $customer->name= strtolower($name);
                $customer->age= $request->input('age');
                $customer->status= $request->input('status');
                $customer->phone= $request->input('phone');
                $customer->user_id= $user['sub'];
                $customer->save();

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'El cliente se agrego correctamente'],200);
            }
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
        $user=$request->input('user');

        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }

        // Creamos las reglas de validación
        $rules = [
            'name'      => 'required',
            'age'      => 'required',
            'status'        => 'required',
            'phone'        => 'required',
        ];

        //Se va a pasar datos del producto, attributos y su cantidad

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para actualizar un cliente'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $customer=Customer::find($id);

            if(count($customer) != 0 ){
                $customer->name= $request->input('name');
                $customer->age= $request->input('age');
                $customer->status= $request->input('status');
                $customer->user_id= $user['sub'];
                $customer->phone= $request->input('phone');
                $customer->save();

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'El cliente se actualizo correctamente'],200);
            }

            return response()->json(['message' => 'El cliente no esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar cliente'], 500);
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

    public function addressAdd(Request $request,$id){
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'ubigeo_id'      => 'required',
            'description'      => 'required',
            'reference'      => 'required',
            //falta validar los atributos
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un direccion'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $customer=Customer::find($id);

            if(count($customer) !== 0 ){
                $direccion = new Address();
                $direccion->customer_id= $id;
                $direccion->ubigeo_id= $request->input('ubigeo_id');
                $direccion->description= $request->input('description');
                $direccion->reference= $request->input('reference');

                $customer->addresses()->save($direccion);

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'La direccion se agrego correctamente'],200);
            }

            return response()->json(['message' => 'El usuario no esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }

    public function phoneAdd(Request $request,$id){
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'operator_id'      => 'required',
            'number'      => 'required',
            //falta validar los atributos
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un telefono'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $customer=Customer::find($id);

            if(count($customer) !== 0 ){
                $phone = new Phone();
                $phone->customer_id= $id;
                $phone->operator_id= $request->input('operator_id');
                $phone->number= $request->input('number');

                $customer->phones()->save($phone);

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'El telefono se agrego correctamente'],200);
            }

            return response()->json(['message' => 'El cliente no esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }

    public function socialAdd(Request $request,$id){
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'channel_id'      => 'required',
            'channel_url'      => 'required',
            
            //falta validar los atributos
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un cuenta'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $customer=Customer::find($id);

            if(count($customer) !== 0 ){
                $social = new Social();
                $social->customer_id= $id;
                $social->channel_id= $request->input('channel_id');
                $social->channel_url= $request->input('channel_url');                

                $customer->socials()->save($social);

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'La cuenta se agrego correctamente'],200);
            }

            return response()->json(['message' => 'El usuario no esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }

    public function addressUpdate(Request $request,$id){
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'ubigeo_id'      => 'required',
            'description'      => 'required',
            'reference'      => 'required',
            //falta validar los atributos
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para actualizar una dirección'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $direccion = Address::find($id);

            if(count($direccion) !== 0 ){
                $customer=Customer::find($direccion->customer_id);
                $direccion->ubigeo_id= $request->input('ubigeo_id');
                $direccion->description= $request->input('description');
                $direccion->reference= $request->input('reference');

                $customer->addresses()->save($direccion);

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'La direccion se actualizo correctamente'],200);
            }

            return response()->json(['message' => 'La direccion no esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }

    }

    public function phoneUpdate(Request $request,$id){
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'operator_id'      => 'required',
            'number'      => 'required',
            //falta validar los atributos
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para actualizar un teléfono'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $phone = Address::find($id);

            if(count($phone) !== 0 ){
                $customer=Customer::find($phone->customer_id);
                $phone = new Phone();
                $phone->operator_id= $request->input('operator_id');
                $phone->number= $request->input('number');

                $customer->phones()->save($phone);

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'El teléfono se actualizo correctamente'],200);
            }

            return response()->json(['message' => 'El usuario no esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar teléfono'], 500);
        }
    }

    public function socialUpdate(Request $request,$id){
        if (!is_array($request->all())) {
            return response()->json(['message' => 'request must be an array'],401);
        }
        // Creamos las reglas de validación
        $rules = [
            'channel_id'      => 'required',
            'channel_url'      => 'required',
        ];


        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para actualizar una cuenta'],401);
            }
            // Si el validador pasa, almacenamos el comentario
            $social=Social::find($id);

            if(count($social) !== 0 ){
                $customer = new Customer($social->customer_id);
                $social->channel_id= $request->input('channel_id');
                $social->channel_url= $request->input('channel_url');

                $customer->socials()->save($social);

                //Falta Agregar atributos de productos
                return response()->json(['message' => 'La cuenta se actualizo correctamente'],200);
            }

            return response()->json(['message' => 'La cuenta no esta registrado'],400);

        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }
}
