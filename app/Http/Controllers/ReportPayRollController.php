<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class ReportPayRollController extends Controller
{
    public function get_assists_for_month($month){
        Carbon::createFromDate(2000,$month,1,-5);


    }
}
