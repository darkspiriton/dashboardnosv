<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Dashboard\Models\notification\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Middleware autorize
     *
     */   
    function __construct(){
        $this->middleware("auth:GOD");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notification = Notification::orderBy("created_at", "desc")->take(20)->get();
        return $notification;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $notification = Notification::where("id", $id)->first();
        $notification->status = 1;
        $notification->save();

        return $notification;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Update last 20 notification
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAll(Request $request)
    {
        if($request->has("count")){
            Notification::take(request("count"))->update(["status" => 1]);
        } else {
            Notification::take(20)->update(["status" => 1]);
        }

        $notification = Notification::orderBy("created_at", "desc")->take(20)->get();

        return $notification;
    }
}
