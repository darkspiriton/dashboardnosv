<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;


class HomeController extends Controller
{
    public function validar(){
        return response()->json(['message'=> 'Autorizacion valida'],200);
    }
}
