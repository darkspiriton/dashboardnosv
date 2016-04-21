<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dashboard\Http\Requests;

class OperadorController extends Controller
{
    public function index(){
        $operadores=DB::table('operators')->get();
        if($operadores !== null){
            return response()->json([
                'message' => 'Mostrar todos los operadores',
                'operadores'=> $operadores,
                //'attributes' => $product->attributes,
            ],200);
        } else{
            return response()->json([
                'message' => 'No se encuentran los operadores'
            ],404);
        }
    }
}
