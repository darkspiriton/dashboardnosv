<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Illuminate\Http\Request;
use Dashboard\Models\Request\User;
use Dashboard\Models\Request\Photo;
use Dashboard\Models\Request\Product;

class RequestProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('user','photos')
            ->where('user_id',null)->get();

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
            // 'status' => 'required',
            // 'user_request_id' => 'integer',
            // 'user_id' => 'integer',
            // 'photos' => 'required|array',
            'nameUser' => 'string',
            'email' => 'email',
            'phone' => 'numeric'
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['message'=>'Parametros recibidos no validos'],401);
        }

        if($request->has('nameUser') && $request->has('email') && $request->has('phone')){            
            $name = strtolower($request->input('nameUser'));
            $email = strtolower($request->input('email'));
            $phone = $request->input('phone');

            $user = User::where('name',$name)
                            ->where('email',$email)
                            ->where('phone',$phone)->get();

            
            //Validar la cantidad de request creados por un usuario
            if(!$user->isEmpty()){
                $cantP = Product::where('user_request_id',$user[0]->id)
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

                if(!$user->isEmpty()){
                    $product->user_request_id=$user[0]->id;
                    // $product->user()->save($user[0]);
                }else{
                    $user = new User();
                    $user->name=$name;
                    $user->email=$email;
                    $user->phone=$phone;
                    $user->save();
                    $product->user()->save($user);
                }      
                $product->save();
                
                $this->saveStorage($request,$product);

                return response()->json(['message' => 'Se registro correctamente el pedido en breve se contactara con usted'],200);
            }else{
                return response()->json(['message' => 'No se registro porque posee muchos pedidos aun por validar'],404);
            }           

        }elseif($request->has('user')['sub']){

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
        $imageName = $request->file($img); // referencia a la imagen
        $image= file_get_contents($request->file($img)->getRealPath());
        $image_name = str_random(4).'_'.$imageName->getClientOriginalName();                
        Storage::disk('public')->put('img/publicities/'.$image_name,$image);

        $photo = new Photo();
        $photo->url = $image_name;
        $photo->product()->save($product);
        $photo->save();
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
        $product = Product::with('user','photos')
                            ->where('id',$id)->get();
        if(!$product->isEmpty()){
            return response()->json(['product'=>$product],200);
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
        $product = Product::find($id);
        if(!$product->isEmpty()){
            $product->delete();
            return response()->json(['message'=>'La petición se elimino correctamente'],200);
        }else{
            return response()->json(['message'=>'No existe una peticion con ese identificador'],401);
        }
    }
}
