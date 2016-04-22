<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dashboard\Http\Requests;

class UbigeoController extends Controller
{
    public function departamento(){
        $departamentos=DB::table('ubigeos')
                    ->distinct('UBIDEN')
                    ->select('UBIDEP','UBIDEN')->get();
        if($departamentos !== null){
            return response()->json([
                'message' => 'Mostrar todas las provincias',
                'departamentos'=> $departamentos,
                //'attributes' => $product->attributes,
            ],200);
        } else{
            return response()->json([
                'message' => 'No se encuentran los departamentos'                
            ],404);
        }
        
    }

    public function provincia($id){
        $provincias=DB::table('ubigeos')
            ->distinct('UBIDEN')
            ->select('UBIPRV','UBIPRN')
            ->where('UBIDEP',$id)
            ->where('UBIPRN','<>','')
            ->get();
        if($provincias !== null){
            return response()->json([
                'message' => 'Mostrar todas las provincias',
                'provincias'=> $provincias,
                //'attributes' => $product->attributes,
            ],200);
        } else{
            return response()->json([
                'message' => 'No se encuentran las provincias'                
            ],404);
        }
        
    }

    public function distrito($id){
        $distritos=DB::table('ubigeos')
            ->distinct('UBIDEN')
            ->select('UBIDST','UBIDSN')
            ->where('UBIPRV',$id)
            ->where('UBIDSN','<>','')
            ->get();
        if($distritos !== null){
            return response()->json([
                'message' => 'Mostrar todas las provincias',
                'distritos'=> $distritos,
                //'attributes' => $product->attributes,
            ],200);
        } else{
            return response()->json([
                'message' => 'No se encuentrarn los distritos'
            ],404);
        }
        
    }
}
