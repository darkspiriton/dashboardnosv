<?php

namespace Dashboard\Http\Controllers;

use Dashboard\User as UserR;
use Dashboard\Http\Requests;
use Illuminate\Http\Request;
use Dashboard\Models\Request\User as User;
use Dashboard\Models\Request\Photo;
use Dashboard\Models\Request\Product;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Config;

class RequestProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('status')){
            $rules = [
                'status' => 'integer|between:1,4',
            ];

            $validator = \Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json(['message' => 'Los valores de estado no estan permitidos'],404);
            }
            elseif($request->input('status') == 4){
                $products = Product::with('photos','user','userR')->get();
            }else{
                $products = Product::with('photos','user','userR')
                                ->where('status',$request->status)->get();
            }

       
        }else{
            $products = Product::with('photos','user','userR')
                                ->where('status',1)
                                ->orderby('created_at','desc')  ->get();    
        }
        
        return response()->json(['products' => $products],200);
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
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' =>  'required|numeric',
            'nameUser' => 'string',
            'email' => 'email',
            'phone' => 'string'
        ];

        $validator = \Validator::make($request->all(),$rules);

        // return $validator->messages();

        if($validator->fails()){
            return response()->json(['message'=>'Parametros recibidos no validos'],401);
        }



        if($request->has('nameUser') && $request->has('email') && $request->has('phone')){            
            $name = strtolower($request->input('nameUser'));
            $email = strtolower($request->input('email'));
            $phone = $request->input('phone');

            $user = User::where('name',$name)
                            ->where('email',$email)
                            ->where('phone',$phone)->first();

            
            //Validar la cantidad de request creados por un usuario
            if($user != null){
                $cantP = Product::where('user_request_id',$user->id)
                                        ->where('status',1)->count();
            }else{
                $cantP = 0;     
            }
           
    
            if($cantP<=10){
                $product = new Product();
                $product->name=$request->input('name');
                $product->description=$request->input('description');
                $product->price=$request->input('price');
                $product->status=1;

                $product->save();

                if($user != null){
                    $product->user_request_id = $user->id;
                    $product->save();
                }else{
                    $user = new User();
                    $user->name = $name;
                    $user->email = $email;
                    $user->phone = $phone;
                    $user->status = 4;
                    $user->save();

                    $product->user_request_id = $user->id;
                    $product->save();
                }      
                
                
                $this->saveStorage($request,$product);

                return response()->json(['message' => 'Se registro correctamente el pedido en breve se contactara con usted'],200);
            }else{
                return response()->json(['message' => 'No se registro porque posee muchos pedidos aun por validar'],404);
            }           

        }elseif($request->has('token')){

        /** 
            helper
        */
            try{
                $payload = (array) JWT::decode(request("token"), Config::get('app.jwt_token'), array('HS256'));

                if($payload == null)
                    return response()->json(['message' => 'El Token Alterado'],401);

                $request['user'] = $payload;
            }catch( DomainException $e){
                return response()->json(['message' => 'El formato de autorizacion no es valido'], 401);
            }catch( ErrorException $e){
                return response()->json(['message' => 'El formato de la cabecera de autorizacion no es valido'], 401);
            }catch (ExpiredException $e){
                return response()->json(['message' => 'El tiempo de autorizacion Expiro'],401);
            }catch (\InvalidArgumentException $e){
                return response()->json(['message' => 'Argumento invalido'],401);
            }catch (\UnexpectedValueException $e){
                return response()->json(['message' => 'Valor inesperado', "error" => $e->getMessage()],401);
            }

        /**
            END
        */

            //capturamos el id de usuario logueado
            $user_id = $request->input('user')['sub'];

            $cantP = Product::where('user_request_id',$user_id)
                                    ->where('status',1)->count();

            if($cantP <= 10){
                $product = new Product();
                $product->name=$request->input('name');
                $product->description=$request->input('description');
                $product->price=$request->input('price');
                $product->status=1;
                $product->user_id=$user_id;
                $product->save();

                $this->saveStorage($request,$product);

                return response()->json(['message' => 'Se registro correctamente el pedido en breve se contactara con usted'],200);
            }else {
                return response()->json(['message' => 'No se registro porque posee muchos pedidos aun por validar'],404);
            }

        }else{
            return response()->json(['message' => 'No se puede registrar pues el usuario no esta validado'],404);
        }

    }

    private function imageStore(Request $request,$img,$product){
        // $imageName = $request->file($img); // referencia a la imagen
        // $image= file_get_contents($request->file($img)->getRealPath());
        // $image_name = str_random(4).'_'.$imageName->getClientOriginalName();                
        // Storage::disk('public')->put('img/publicities/'.$image_name,$image);

        $image = $request->file($img); // referencia a la imagen
        $image_name = str_random(4).'_'.$image->getClientOriginalName();
        $image_folder = 'img/request/';
        $image->move(public_path($image_folder), $image_name); // moviendo imagen a images folder

        $photo = new Photo();
        $photo->url = $image_name;

        $product->photos()->save($photo);
    }

    private function saveStorage(Request $request,$product){
        if($request->hasFile('img1')){            
            $this->imageStore($request,'img1',$product);
        }

        if($request->hasFile('img2')){
            $this->imageStore($request,'img2',$product);
        }

        if($request->hasFile('img3')){
            $this->imageStore($request,'img3',$product);
        }

        if($request->hasFile('img4')){
            $this->imageStore($request,'img4',$product);
        }

        if($request->hasFile('img5')){
            $this->imageStore($request,'img5',$product);
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
        // return Photo::all();
        $product = Product::with('photos')
                            ->where('id',$id)->get();

        if(!$product->isEmpty()){       
            return response()->json(['product'=>$product ],200);
        }else{
            return response()->json(['message'=>'No existe una peticion con ese identificador'],401);
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
        $product = Product::find($id);

        if($product !== null){     
            if($product->status==1)       {
                $product->status=2;
            }elseif($product->status==2){
                $product->status=3;
            }elseif($product->status==3){
                return response()->json(['message' => 'No se puede cambiar el estado de un pedido rechazado'],401);
            }
            $product->save();
            return response()->json(['message'=>'Se actualizo correctamente el estado de la petición'],200);
        }else{
            return response()->json(['message'=>'No existe una peticion con ese identificador'],401);
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
        $product = Product::find($id);

        if($product !== null){            
            $product->photos()->delete();
            $product->delete();
            return response()->json(['message'=>'La petición se elimino correctamente'],200);
        }else{
            return response()->json(['message'=>'No existe una peticion con ese identificador'],401);
        }
    }

    public function status(){
        $status = [
            ['id' => 1, 'name' => 'Sin Atender'],
            ['id' => 2, 'name' => 'Atendido'],
            ['id' => 3, 'name' => 'Rechazado'],
            ['id' => 4, 'name' => 'Todos']
        ];
        return response()->json(['estados' => $status],200);
    }

    public function getUser(Request $request, $id){
        $rules = [
            'status' => 'required|integer|between:0,1'            
        ];

        $validator = \Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['message' => 'No se cuenta con lo valores validos'],401);
        }

        if($request->status == 1){
            $user = User::find($id);
        }elseif($request->status == 0){
            $userAux = UserR::find($id);

            $user = new User();
            $user->id = $id;
            $user->name = $userAux->first_name.' '.$userAux->last_name;
            $user->email= $userAux->email;
            $user->phone = $userAux->phone;
            $user->created_at= $userAux->created_at;
            $user->updated_at=$userAux->updated_at;
        }

        return response()->json(['user' => $user ],200);
    }

}
;
