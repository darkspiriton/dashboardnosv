<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Illuminate\Http\Request;
use Dashboard\Models\Prestashop\User;
use Dashboard\Models\Prestashop\Product;
use Dashboard\Models\Prestashop\Request as RequestP;

class PrestaShopController extends Controller
{
    // public function __constructor()
    // {
    //     $this->middleware('auth:GOD,ADM,JVE');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('status')){
           $requestP =  RequestP::with('user')->where('status',$request->input('status'))->get();
        }else{
           $requestP =  RequestP::with('user','products')->where('status',0)->get();
        }

        if($requestP == null){
            return response()->json(['message' => 'No se encontro pedidos disponibles'],401);
        }else{
            return response()->json(['pedidos' => $requestP],200);
        }       
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
            'name_user' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'total_price' => 'required|numeric',
            'products'  => 'required|array',
            'products.*.name_product' => 'required|string',
            'products.*.url_image' => 'required|string',
            'products.*.url_product' => 'required|string',
            'products.*.stock' => 'required|integer',
            'products.*.price' => 'required|price',
            'products.*.cant'  => 'required|integer',            
        ];

        $validator = \Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['message' => 'No se cuenta con todos los campos necesarios para crear pedido ventas'],401);
        }

        $name = strtolower($request->input('name_user'));
        $email = strtolower($request->input('email'));
        $phone = $request->input('phone');

        $user = User::where('name',$name)
                        ->where('email',$email)
                        ->where('phone',$phone)->first();


        $products = array();
        if($user != null){

           $request = new RequestP();
           $request->status=0;
           $request->total_price=$request->input('total_price');
           $request->user_id=$user->id;
           $request->save();

        }else{

            $userAux = new User();
            $userAux->name = $name;
            $userAux->email = $email;
            $userAux->phone = $phone;
            $userAux->save();

            $request = new RequestP();
            $request->status=0;
            $request->total_price=$request->input('total_price');
            $request->user_id=$userAux->id;
            $request->save();  

        }

        foreach($request->input('products') as $i => $product){
            $mProduct= new Product();
            $mProduct->name=$product->name_product;
            $mProduct->url_image=$product->url_image;
            $mProduct->url_product=$product->url_product;
            $mProduct->stock=$product->stock;
            $mProduct->price=$product->price;
            $mProduct->cant=$product->cant;
            $mProduct->request_id=$request->id;
            $products[] = $mProduct;
        }

        $request->products()->saveMany($products);

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
        $requestP = RequestP::find($id);
        if($requestP == null){
            return response()->json(['message' => 'No existe este pedido']);
        }else{
            if($requestP->status == 0){
                $requestP->status=1;
                $requestP->save();
                return response()->json(['message' => 'Se actualizo el estado del pedido'],200);
            }elseif($requestP->status == 1){
                return response()->json(['message' => 'Este pedido ya fue atendido'],200);
            }
        }
        return response()->json(['message' => $request],200);
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
