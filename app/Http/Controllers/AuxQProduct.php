<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Dashboard\Models\Questionnaire\Customer;
use Dashboard\Models\Questionnaire\Product;
use Dashboard\Http\Requests;

class AuxQProduct extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:GOD,ADM');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::all();
        return response()->json(['productos'=>$products]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $number=$this->cuestionario();

//        $request->input('questionnaire_id')

        $products=Product::with(['answers'=>function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        },'answers.option'])->where('id','=',$id)
            ->get();

        if($products==null){
            return response()->json(['message'=>'No se encontro producto asociado']);
        }

        $customers = Customer::with(['answers.option','answers' => function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        }])->get();

        if($customers==null){
            return response()->json(['message'=>'No se encontro usuarios asociados']);
        }

        $product=$products[0];
//        $customer=$customers[0];

        //respuestas a las preguntas
        $cantAP=$product['answers']->count();
        if($cantAP==0){
            return response()->json(['message'=>'No se posee cuestionario resuelto para este producto']);
        }
        $z=0;
        $countC = 0;

        $resultados=Array();
        $cod0=Array();
        $cod1=Array();
        $cod2=Array();
        $cod3=Array();
        $cod4=Array();
        $cod5=Array();
        $resultados[0]=0;
        $resultados[1]=0;
        $resultados[2]=0;
        $resultados[3]=0;
        $resultados[4]=0;
        $resultados[5]=0;
        $x1=$x2=$x3=$x4=$x5=$x0=0;
        foreach($customers as $customer) {
            $idC=$customer['id'];
            for ($i = 0; $i < $cantAP; $i++) {
                //obtenemos el id de la pregunta y id de la respuesta del usuario
                $idPP = (int)$product['answers'][$i]['option']['question_id'];
                $idPR = (int)$product['answers'][$i]['option']['id'];

                //cant de respuestas de un producto y sus respuestas
                $cantAC = $customer['answers']->count();
                $j = 0;
                $termino = false;

                while ($termino == false and $j < $cantAC) {

                    //obtenemos el id de la pregunta y id de la respuesta del producto
                    $idCP = (int)$customer['answers'][$j]['option']['question_id'];
                    $idCR = (int)$customer['answers'][$j]['option']['id'];
                    if ($idPP == $idCP) {
                        if ($idPR == $idCR) {
                            //Coincidencia se agrega 1 contador y el id del product
                            $termino = true;
                            $countC++;
                        }
                    }
                    $j++;
                }
            }

            $porcentaje = round(100 * $countC / $cantAP, 2);
            if($porcentaje==0){
                $resultados[0]=$resultados[0]+1;
                $cod0[$x0]=$idC;
                $codigos[0]= $cod0;
                $x0++;
            }elseif(0< $porcentaje and $porcentaje<=20){
                $resultados[1]=$resultados[1]+1;
                $cod1[$x1]=$idC;
                $codigos[1]= $cod1;
                $x1++;
            }elseif(20< $porcentaje and $porcentaje<=40){
                $resultados[2]=$resultados[2]+1;
                $cod2[$x2]=$idC;
                $codigos[2]= $cod2;
                $x2++;
            }elseif(40< $porcentaje and $porcentaje<=60){
                $resultados[3]=$resultados[3]+1;
                $cod3[$x3]=$idC;
                $codigos[3]= $cod3;
                $x3++;
            }elseif(60< $porcentaje and $porcentaje<=80){
                $resultados[4]=$resultados[4]+1;
                $cod4[$x4]=$idC;
                $codigos[4]= $cod4;
                $x4++;
            }elseif(80< $porcentaje and $porcentaje<=100){
                $resultados[5]=$resultados[5]+1;
                $cod5[$x5]=$idC;
                $codigos[5]= $cod5;
                $x5++;
            }

            $z++;
            $countC = 0;
        }

        return response()->json(['cantidades'=>$resultados,'codigos'=>$codigos]);
    }
    
    public function mostrar($id){
        $product=Product::find($id);
        return response()->json(['producto'=>$product]);
    }

    public function cuestionario(){
        $product=Product::with(['answers'=>function($query){
            $query->orderby('questionnaire_id','desc');
        }])->get();
        $number=$product[0]['answers'][0]['questionnaire_id'];
        return $number;
    }

}

