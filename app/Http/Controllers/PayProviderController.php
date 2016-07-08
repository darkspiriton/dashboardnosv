<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Product;
use Dashboard\Models\PaymentProvider\Detail;
use Dashboard\Models\PaymentProvider\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Dashboard\Http\Requests;

class PayProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rules = [
            'id'    => 'required|integer|exists:providers,id'
        ];

        try{
            $validator = \Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json(['message' => 'No posee todo los campos necesario para la consulta de pagos'],401);
            }
            $status = 'vendido';
            $products=DB::table('auxproducts as p')
                ->select('m.date_shipment as fecha', 'p.cod as codigo', 'p.name as product', 'c.name as color',
                    DB::raw('case when d.price then d.price else p.cost_provider + p.utility end as price'), 's.name as talla', 'm.status', 'm.discount',
                    DB::raw('case when d.price then d.price-m.discount else p.cost_provider + p.utility -m.discount end as pricefinal'),
                    DB::raw('case when d.price then 1 else 0 end as liquidacion'), 'p.cost_provider as cost','p.payment_status as statusPayment')
                ->join('auxmovements as m', 'p.id', '=', 'm.product_id')
                ->join('colors as c', 'c.id', '=', 'p.color_id')
                ->join('sizes as s', 's.id', '=', 'p.size_id')
                ->leftJoin('settlements AS d', 'd.product_id', '=', 'p.id')
                ->where('m.status', 'like', '%'.$status.'%')
//            ->where('m.situation',null)
                ->where('p.provider_id', $request->input('id'))
//                ->where(DB::raw('DATE(m.date_shipment)'), '>=', $this->start)
//                ->where(DB::raw('DATE(m.date_shipment)'), '<', $this->end)
                ->orderby('p.name', 'c.name', 's.name')
                ->get();

            $payments = DB::table('providers as pro')
                ->join('payments_providers as pp','pro.id','=','pp.provider_id')
                ->where('pro.id',$request->input('id'))
                ->orderby('pp.date','desc')
                ->get();

            return \Response::json(['products' => $products,'payments' => $payments]);

        }catch(\Exception $e){
            return \Response::json(['message' => 'No se pudo listar los pagos del proveedor por error en el servidor'],500);
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
            'provider_id'   => 'required|integer|exists:providers,id',
            'products' => 'required|array',
            'products.*.id' =>  'required|integer|exists:auxproducts,id',
            'date' =>   'required|date',
            'type_payment'  =>  'required|numeric',
            'amount'    => 'required|numeric',
            'discount'  => 'required|numeric',
            'reason'    => 'required'
        ];

        try{
            $validator = \Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json(['message'=>'No posee todo los campos necesario para crear un pago'],401);
            }
            
            $products= $request->input('products');
            
            $payment= new Payment();
            $payment->provider_id = $request->input('provider_id');
            $payment->type_discount_id = 1;
            $payment->name_bank = "Interbank";
            $payment->date = $request->input('date');
            $payment->amount = $request->input('amount');
            $payment->discount = $request->input('discount');
            $payment->reason = $request->input('reason');
            $payment->save();
            
            foreach($products as $product){
                $detail = new Detail();
                $detail->payment_id = $payment->id;
                $detail->product_id = $product['id'];
                $detail->save();

                $auxProduct = Product::find($detail->product_id);
                $auxProduct->payment_status = 1;
                $auxProduct->save();
            }    

            return response()->json(['message' => 'Los pagos se registraron correctamente'],200);

        }catch(\Exception $e){
            return response()->json(['message' => 'Ocurrio un error en el servidor'],500);
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
        //actualizar datos de pago de producto en conjunto
        $rules = [
            'provider_id'   => 'required|integer|exists:providers,id',
            'products' => 'required|array',
            'products.*.id' =>  'required|integer|exists:auxproducts,id',
            'date' =>   'required|date',
            'type_payment'  =>  'required|numeric',
            'amount'    => 'required|numeric',
            'discount'  => 'required|numeric',
            'reason'    => 'required'
        ];

        try{
            $validator= \Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json(['message' => 'No posee todos los campos para actualizar el producto'],401);
            }

            $products = $request->input('products');

            $payment = Payment::find($id);
            $payment->provider_id = $request->input('provider_id');
            $payment->type_discount_id = 1;
            $payment->name_bank = "Interbank";
            $payment->date = $request->input('date');
            $payment->amount = $request->input('amount');
            $payment->discount = $request->input('discount');
            $payment->reason = $request->input('reason');
            $payment->save();

            $details = $payment->details();

            $existe=false;
            foreach ($details as $detail){
                foreach ($products as $product){
                    if($product['id']==$detail->product_id){
                        $existe=true;
                       break;
                    }
                }
                if ($existe==false){
                    $auxProduct= Product::find($detail->product_id);
                    $auxProduct->provider_status=0;
                    $auxProduct->save();
                }else {
                    $existe = false;
                }
            }



        }catch(\Exception $e){
            return response()->json(['message' => 'Ocurrio un error al actualizar los datos en el servidor' ],200);
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

            //eliminar el pago en conjunto de producto
            $payment = Payment::find($id);

            $details=$payment->details();

            foreach($details as $detail){
                $product=Product::find($detail->product_id);
                $product->provider_status=0;
                $product->save();
                $detail->delete();
            }
            $payment->delete();

            return response()->json(['message'=>'Se elimino correctamente'],200);

        }catch(\ErrorException $e){
            return response()->json(['message' => 'Ocurrio un error al eliminar'],500);
        }
    }
}
