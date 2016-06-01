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
        $this->middleware('auth:GOD,ADM');
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers=Customer::all();
        return response()->json(['customers'=>$customers]);
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

        $customers = Customer::with(['answers.option','answers' => function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        }])->where('id','=',$id)
            ->get();
        
        if($customers==null){
            return response()->json(['message'=>'No existe el empleado'],404);
        }

        $products=Product::with(['answers'=>function($query) use($number){
            $query->where('questionnaire_id','=',$number);
        },'answers.option'])->get();
        
        if($products->count() == 0){
            return response()->json(['message'=>'No existe productos relacionados'],404);
        }

        $customer=$customers[0];

        //respuestas a las preguntas
        $cantAC=$customer['answers']->count();
        
        if($cantAC==null){
            return response()->json(['message'=>'No existe cuestionario resueltos del cliente'],404);
        }

        $z=0;
        $countP = 0;

        $resultados=Array();
        $cod0=Array();
        $cod1=Array();
        $cod2=Array();
        $cod3=Array();
        $cod4=Array();
        $cod5=Array();
        $cod6=Array();

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
        foreach($products as $product) {
            $idP=$product['id'];
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
            }

            $porcentaje = round(100 * $countP / $cantAC, 2);
            if($porcentaje==0){
                $resultados[0]['count']=$resultados[0]['count']+1;
                $resul_0[0]['col_1'] += 1;
                $cod0[$x0]=$idP;
                $codigos[0]= $cod0;
                $x0++;
            }elseif(0< $porcentaje and $porcentaje<=20){
                $resultados[1]['count']=$resultados[1]['count']+1;
                $resul_0[0]['col_2'] += 1;
                $cod1[$x1]=$idP;
                $codigos[1]= $cod1;
                $x1++;
            }elseif(20< $porcentaje and $porcentaje<=40){
                $resultados[2]['count']=$resultados[2]['count']+1;
                $resul_0[0]['col_3'] += 1;
                $cod2[$x2]=$idP;
                $codigos[2]= $cod2;
                $x2++;
            }elseif(40< $porcentaje and $porcentaje<=60){
                $resultados[3]['count']=$resultados[3]['count']+1;
                $resul_0[0]['col_4'] += 1;
                $cod3[$x3]=$idP;
                $codigos[3]= $cod3;
                $x3++;
            }elseif(60< $porcentaje and $porcentaje<=80){
                $resultados[4]['count']=$resultados[4]['count']+1;
                $resul_0[0]['col_5'] += 1;
                $cod4[$x4]=$idP;
                $codigos[4]= $cod4;
                $x4++;
            }elseif(80< $porcentaje and $porcentaje<100){
                $resultados[5]['count']=$resultados[5]['count']+1;
                $resul_0[0]['col_6'] += 1;
                $cod5[$x5]=$idP;
                $codigos[5]= $cod5;
                $x5++;
            }elseif($porcentaje==100){
                $resultados[6]['count']=$resultados[6]['count']+1;
                $resul_0[0]['col_7'] += 1;
                $cod6[$x6]=$idP;
                $codigos[6]= $cod6;
                $x6++;
            }

            $z++;
            $countP = 0;
        }

        return response()->json(['cantidades'=>$resultados,'codigos'=>$codigos, 'row' => $resul_0],200);
    }

    public function cuestionario(){

        $cliente=Customer::with(['answers'=>function($query){
            $query->orderby('questionnaire_id','desc');
        }])->get();
        $number=$cliente[0]['answers'][0]['questionnaire_id'];
        
        return $number;
    }

}
