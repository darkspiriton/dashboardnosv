<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Questionnaire\Customer;
use Dashboard\Models\Questionnaire\Product;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;


class AuxQCustomer extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:GOD,ADM,JVE,VEN');
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $number=3;
        $customers = Customer::with(['answers.option','answers' => function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        }])->where('id','=',1)
            ->get();
//        $customers = Customer::all();
//        dd($customers);
//        foreach ($customers as $customer){
//            dd($customer['answers']->count());
//        }
//        $custo=$customer->pluck('attributes');

        $products=Product::with(['answers'=>function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        },'answers.option'])->get();

        foreach ($customers as $customer){

            //respuestas a las preguntas
            $cantAC=$customer['answers']->count();

            $z=0;
            $countP = 0;
            foreach($products as $product) {
                for ($i = 0; $i < $cantAC; $i++) {

                    //obtenemos el id de la pregunta y id de la respuesta del usuario
                    $idCP = (int)$customer['answers'][$i]['option']['question_id'];
                    $idCR = (int)$customer['answers'][$i]['option']['id'];
                    
                    //cant de respuestas de un producto y sus respuestas
                    $cantAP = $product['answers']->count();
                    $j = 0;
                    $termino = false;
                    while ($termino == false and $j < $cantAP) {

                        //obtenemos el id de la pregunta y id de la respuesta del producto
                        $idPP = (int)$product['answers'][$j]['option']['question_id'];
                        $idPR = (int)$product['answers'][$j]['option']['id'];
                        if ($idCP == $idPP) {
                            if ($idCR == $idPR) {
                                //Coincidencia se agrega 1 contador y el id del product
                                $termino = true;
                                $countP++;
                            }
                        }
                        $j++;
                    }
      
                    $porcentaje[$z] = round(100 * $countP / $cantAC, 2);

                }
                $z++;
            }
            dd($porcentaje);
        }


        return response()->json(['customer'=>$customer,'product'=>$products]);
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
        //
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
        //
    }
}
