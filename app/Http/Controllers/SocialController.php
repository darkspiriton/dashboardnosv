<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dashboard\Http\Requests;

class SocialController extends Controller
{
    public function index(){
        $socials=DB::table('channels')->get();
        if($socials !== null){
            return response()->json([
                'message' => 'Mostrar todos los canales',
                'socials'=> $socials,
                //'attributes' => $product->attributes,
            ],200);            
        } else{
            return response()->json([
                'message' => 'No se encuentran los canales'
            ],404);
        }
    }
}
