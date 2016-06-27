<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

use Dashboard\Http\Requests;

class PartnerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:all');
    }

    /**
     * Top 5 sales products
     */

    public function TopSales(Request $request){

        if(\Validator::make($request->all(), ['filter' => 'integer|between:1,4'])->fails())
            return response()->json(['message' => 'parametros no validos']);
        
        $dates = $this->getDate($request->input('filter'));

        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id','=',3)->groupBy('name')->lists('name');

        $top = DB::table('auxmovements as m')->select('p.name', DB::raw('count(`p`.`name`) as quantity'))
                        ->join('auxproducts as p','p.id','=','m.product_id')
                        ->whereIn('p.name',$products)
                        ->where('m.status','=','Vendido')
                        ->where('m.date_shipment','>',$dates['start'])
                        ->where('m.date_shipment','<',$dates['end'])
                        ->groupBy('p.name')
                        ->orderBy('quantity','desc')
                        ->take(5)->get();

        return response()->json(['TopSales' => $top],200);
    }

    public function TopLessSold(){
        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id','=',3)->groupBy('name')->lists('name');

        $top = DB::table('auxproducts')->select('name', DB::raw('count(`name`) as quantity'))
            ->whereIn('name',$products)
            ->where('status','<>',2)
            ->groupBy('name')
            ->orderBy('quantity','desc')
            ->take(5)->get();

        return response()->json(['TopLessSold' => $top],200);
    }

    private function getDate($option){

        $now = Carbon::now();

        $dates = array();

        if($option == null){
            $dates['start'] = $now->copy()->firstOfMonth();
            $dates['end'] = $now->copy()->lastOfMonth();
        } else {
            switch ($option){
                case 1: // 1ra quincena
                    $dates['start'] = $now->copy()->firstOfMonth();
                    $dates['end']   = $dates['start']->copy()->addWeeks(2);
                    break;
                case 2: // 2da quincena
                    $dates['start'] = $now->copy()->firstOfMonth()->addWeeks(2);
                    $dates['end']   = $now->copy()->lastOfMonth();
                    break;
                case 3: // Semana actual
                    $dates['start'] = $now->copy()->startOfWeek();
                    $dates['end']   = $now->copy()->endOfWeek();
                    break;
                default:    // Mes actual
                    $dates['start'] = $now->copy()->firstOfMonth();
                    $dates['end']   = $now->copy()->lastOfMonth();
            }
        }

        return $dates;

    }
}
