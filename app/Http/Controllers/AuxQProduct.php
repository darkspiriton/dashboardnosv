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
        $number = $this->cuestionario();

        $products = Product::with(['answers'=>function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        },'answers.option'])->where('id','=',$id)
            ->get();

        if($products == null){
            return response()->json(['message'=>'No se encontro producto asociado'],404);
        }

        $customers = Customer::with(['answers.option','answers' => function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        }])->get();

        if($customers->count() == 0){
            return response()->json(['message'=>'No se encontro usuarios asociados'],404);
        }

        $product=$products[0];
//        $customer=$customers[0];

        //respuestas a las preguntas
        $cantAP=$product['answers']->count();
        if($cantAP==0){
            return response()->json(['message'=>'No se posee cuestionario resuelto para este producto'],404);
        }
        $z = 0;
        $countC = 0;

        $resultados = Array();
        $cod0 = Array();
        $cod1 = Array();
        $cod2 = Array();
        $cod3 = Array();
        $cod4 = Array();
        $cod5 = Array();
        $cod6 = Array();

        // 6 filas
        $resultados[0]['count'] = 0;
        $resultados[1]['count'] = 0;
        $resultados[2]['count'] = 0;
        $resultados[3]['count'] = 0;
        $resultados[4]['count'] = 0;
        $resultados[5]['count'] = 0;
        $resultados[6]['count'] = 0;

        // 1 fila
        $resul_0[0]['col_1'] = 0;
        $resul_0[0]['col_2'] = 0;
        $resul_0[0]['col_3'] = 0;
        $resul_0[0]['col_4'] = 0;
        $resul_0[0]['col_5'] = 0;
        $resul_0[0]['col_6'] = 0;
        $resul_0[0]['col_7'] = 0;

        // labels
        $resultados[0]['label'] = '0%';
        $resultados[1]['label'] = '0% - 20%';
        $resultados[2]['label'] = '20% - 40%';
        $resultados[3]['label'] = '40% - 60%';
        $resultados[4]['label'] = '60% - 80%';
        $resultados[5]['label'] = '80% - 100%';
        $resultados[6]['label'] = '100%';

        $x1=$x2=$x3=$x4=$x5=$x0=$x6=0;
        foreach($customers as $customer) {
            $idC = $customer['id'];
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
                $resultados[0]['count']=$resultados[0]['count']+1;
                $resul_0[0]['col_1'] += 1;
                $cod0[$x0]=$idC;
                $codigos[0]= $cod0;
                $x0++;
            }elseif(0< $porcentaje and $porcentaje<=20){
                $resultados[1]['count']=$resultados[1]['count']+1;
                $resul_0[0]['col_2'] += 1;
                $cod1[$x1]=$idC;
                $codigos[1]= $cod1;
                $x1++;
            }elseif(20< $porcentaje and $porcentaje<=40){
                $resultados[2]['count']=$resultados[2]['count']+1;
                $resul_0[0]['col_3'] += 1;
                $cod2[$x2]=$idC;
                $codigos[2]= $cod2;
                $x2++;
            }elseif(40< $porcentaje and $porcentaje<=60){
                $resultados[3]['count']=$resultados[3]['count']+1;
                $resul_0[0]['col_4'] += 1;
                $cod3[$x3]=$idC;
                $codigos[3]= $cod3;
                $x3++;
            }elseif(60< $porcentaje and $porcentaje<=80){
                $resultados[4]['count']=$resultados[4]['count']+1;
                $resul_0[0]['col_5'] += 1;
                $cod4[$x4]=$idC;
                $codigos[4]= $cod4;
                $x4++;
            }elseif(80< $porcentaje and $porcentaje<100){
                $resultados[5]['count']=$resultados[5]['count']+1;
                $resul_0[0]['col_6'] += 1;
                $cod5[$x5]=$idC;
                $codigos[5]= $cod5;
                $x5++;
            }elseif($porcentaje==100){
                $resultados[6]['count']=$resultados[6]['count']+1;
                $resul_0[0]['col_7'] += 1;
                $cod6[$x6]=$idC;
                $codigos[6]= $cod6;
                $x6++;
            }

            $z++;
            $countC = 0;
        }

        return response()->json(['cantidades'=>$resultados,'codigos'=>$codigos, 'row' => $resul_0],200);
    }

    public function mostrar($id){
        $product=Product::find($id);
        return response()->json(['producto'=>$product]);
    }

    public function cuestionario(){
        $product = Product::with(['answers'=>function($query){
            $query->orderby('questionnaire_id','desc');
        }])->get();
        $number = $product[0]['answers'][0]['questionnaire_id'];
        return $number;
    }

}

