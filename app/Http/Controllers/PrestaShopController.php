<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Events\OrderPrestashopWasCreated;
use Dashboard\Http\Requests;
use Dashboard\Models\Prestashop\Product;
use Dashboard\Events\PrestaShopWasCreated;
use Dashboard\Models\Prestashop\Request as RequestP;
use Dashboard\Models\Prestashop\User;
use Illuminate\Http\Request;

class PrestaShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:GOD,NOS,ADM,JVE,VEN', ["except" => ["store"]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('status')) {
            $rules = [
                'status' => 'integer|between:0,2',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'Los valores de estado no estan permitidos'], 404);
            }
            if ($request->input('status')==2) {
                $requestP =  RequestP::with('user')->get();
            } else {
                $requestP =  RequestP::with('user')->where('status', $request->input('status'))->get();
            }
        } else {
            $requestP =  RequestP::with('user')->where('status', 0)->get();
        }

        if ($requestP->isEmpty()) {
            return response()->json(['message' => 'No se encontro pedidos disponibles'], 200);
        } else {            
            return response()->json(['pedidos' => $requestP], 200);
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
            'name_user' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'products'  => 'required|array',
            'products.*.name_product' => 'required|string',
            'products.*.url_image' => 'required|string',
            'products.*.url_product' => 'required|string',
            'products.*.stock' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.quantity'  => 'required|integer',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => 'No se cuenta con todos los campos necesarios para crear pedido ventas'], 401);
        }

        $total_price = 0;
        foreach ($request->input('products') as $i => $product) {
            $price = $product["price"];
            $cant = $product["quantity"];
            $total_price = $total_price + $price * $cant;
        }

        $user = User::where('name', strtolower(request("name_user")))
                        ->where('email', strtolower(request("email")))
                        ->where('phone', request("phone"))->first();

        $products = array();
        if ($user != null) {
            $requestP = new RequestP();
            $requestP->status = 0;
            $requestP->total_price = $total_price;
            $requestP->user_id = $user->id;
            $requestP->save();
        } else {
            $userAux = new User();
            $userAux->name =  strtolower(request("name_user"));
            $userAux->email = strtolower(request("email"));
            $userAux->phone = request("phone");
            $userAux->save();

            $requestP = new RequestP();
            $requestP->status = 0;
            $requestP->total_price = $total_price;
            $requestP->user_id = $userAux->id;
            $requestP->save();
        }

        foreach ($request->input('products') as $i => $product) {
            $mProduct= new Product();
            $mProduct->name         =   $product["name_product"];
            $mProduct->url_image    =   $product["url_image"];
            $mProduct->url_product  =   $product["url_product"];
            $mProduct->stock        =   $product["stock"];
            $mProduct->price        =   $product["price"];
            $mProduct->cant         =   $product["quantity"];
            $mProduct->request_id   =   $requestP->id;
            $products[] = $mProduct;
        }

        $requestP->products()->saveMany($products);

        event(new OrderPrestashopWasCreated([
                                                "person"        =>  ($user)?$user:$userAux,
                                                "products"      =>  $products,
                                                "total_price"   =>  "S/ ".number_format((float)$total_price, 2, '.', ''),
                                                "order"         =>  $requestP
                                            ]));
        // Evento de envio de SMS
        // event(new PrestaShopWasCreated($requestP));

        return ["message" => "Su pedido se registro con exito, el mismo sera enviado a su correo y lo estaremos contactando a la brevedad."];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $requestP = RequestP::with('user', 'products')->where('id', $id)->get();
        if ($requestP->isEmpty()) {
            return response()->json(['message'=>'El pedido que usted busca no existe'], 404);
        }
        return response()->json(['pedido' => $requestP], 200);
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
        if ($requestP == null) {
            return response()->json(['message' => 'No existe este pedido']);
        } else {
            if ($requestP->status == 0) {
                $requestP->status=1;
                $requestP->save();
                return response()->json(['message' => 'Se actualizo el estado del pedido'], 200);
            } elseif ($requestP->status == 1) {
                return response()->json(['message' => 'Este pedido ya fue atendido'], 200);
            }
        }
        return response()->json(['message' => $request], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requestP = RequestP::find($id);
        if ($requestP==null) {
            return response()->json(['message'=>'No existe este pedido'], 404);
        } else {
            $requestP->products()->delete();
            $requestP->delete();
            return response()->json(['message'=>'El pedido fue eliminado correctamente'], 200);
        }
    }

    public function detailProduct($id)
    {
        $products=Product::where('request_id', $id)->get();
        if ($products->isEmpty()) {
            return response()->json(['message' => 'No existen productos asociados a este pedido'], 404);
        }
        return response()->json(['products'=>$products,'message'=>'Detalle de producto correcto'],200);

    }

    public function status()
    {
        $status = [
            ['id' => 0, 'name' => 'No Atendido'],
            ['id' => 1, 'name' => 'Atendido'],
            ['id' => 2, 'name' => 'Todos']
        ];
        return response()->json(['estados' => $status], 200);
    }
}
