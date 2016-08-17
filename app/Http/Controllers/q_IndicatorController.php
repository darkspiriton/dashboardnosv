<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Questionnaire\Customer;
use Dashboard\Models\Questionnaire\Product;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class q_IndicatorController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:GOD,NOS');
    }

    public function showProducts(Request $request){

        if(\Validator::make($request->all(),['codes' => 'required|array'])->fails())
            return response()->json(['message' => 'Terminos de busqueda erroneos'],401);

        $records = Product::with('category')->whereIn('id', $request->input('codes'))->get();

        return response()->json(['records' => $records],200);
    }

    public function showCustomers(Request $request){

        if(\Validator::make($request->all(),['codes' => 'required|array'])->fails())
            return response()->json(['message' => 'Terminos de busqueda erroneos'],401);

        $records = Customer::whereIn('id', $request->input('codes'))->get();

        return response()->json(['records' => $records],200);
    }
}
