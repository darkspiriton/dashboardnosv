<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Product;
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

    public function TopSales(){
        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id','=',3)->groupBy('name')->lists('name');

        $top = DB::table('auxproducts')->select('name', DB::raw('count(`name`) as quantity'))
                        ->whereIn('name',$products)
                        ->where('status','=',2)
                        ->groupBy('name')
                        ->orderBy('quantity','desc')
                        ->get();

        return response()->json(['TopSales' => $top],200);
    }

    public function TopLessSold(){
        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id','=',3)->groupBy('name')->lists('name');

        $top = DB::table('auxproducts')->select('name', DB::raw('count(`name`) as quantity'))
            ->whereIn('name',$products)
            ->where('status','<>',2)
            ->groupBy('name')
            ->orderBy('quantity','desc')
            ->get();

        return response()->json(['TopLessSold' => $top],200);
    }
}
