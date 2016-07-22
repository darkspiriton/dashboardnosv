
<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use Dashboard\Models\Publicity\Publicity;
use Dashboard\Models\Publicity\Social;
use Dashboard\Models\Publicity\TypeSocial;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class AuxSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socials = TypeSocial::all();
        return response()->json(['socials' => $socials],200);
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
        $rules = [
            'publicity_id'  =>  'required|integer|exists:publicities,id',
            'social_id'  =>  'required|integer|exists:types_socials,id'
        ];

        if(\Validator::make($request->all(),$rules)->fails())
            return response()->json(['message' => 'parametros recibidos no son validos'],401);


        $publicity = Publicity::find($request->input('publicity_id'));

        if(Social::where('publicity_id','=',$request->input('publicity_id'))
                    ->where('type_social_id','=',$request->input('social_id'))->get()->count() > 0)
            return response()->json(['message' => 'Esta red social ya esta asociada a esta publicidad'],401);

        $publicity_social = new Social();
        $publicity_social->date = Carbon::now()->toDateString();
        $publicity_social->type_social_id = $request->input('social_id');
        $publicity->socials()->save($publicity_social);

        return response()->json(['message' => 'Se agrego la red social publicada'],200);
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
        $rules = [
            'fb_id' =>  'required',
        ];

        if(\Validator::make($request->all(), $rules)->fails())
            return response()->json(['message' => 'Parametros invalidos'],401);

        Publicity::where('id','=',$id)->update(['facebookID' => $request->input('fb_id')]);

        $request['publicity_id'] = $id;
        $request['social_id'] = 1;

        return $this->store($request);
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
}
