<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function validar(){
        return response()->json(['message'=> 'Autorizacion valida'],200);
    }
}
