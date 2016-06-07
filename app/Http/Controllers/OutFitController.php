<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Experimental\Outfit;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class OutFitController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:GOD,ADM');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outfits = Outfit::all();

        return response()->json(['outfits' => $outfits],200);
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
            'description'   =>  'required|string|max:255',
            'code'          =>  'required|integer|unique:outfits,cod',
            'price'         =>  'required|numeric',
            'status'        =>  'required|integer|between:0,1'
        ];

        if(\Validator::make($request->all(),$rules)->fails())
            return response()->json(['message' => 'Parametros requeridos no son validos'],401);

        $outfit = new Outfit();
        $outfit->name = $request->input('description');
        $outfit->cod = $request->input('code');
        $outfit->price = $request->input('price');
        $outfit->status = $request->input('status');
        $outfit->save();

        foreach ($request->input('products') as $product){
            $products = Product::select('id')->where('name','=',$product['name'])->get();
            $outfit->products()->saveMany($products);
        }

        return response()->json(['message' => 'Se registro el outfit'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $outfit = Outfit::with(['products' => function($query){
            return $query->select('name')->groupBy('name')->orderBy('name','asc');
        }])->where('id','=',$id)->first();

        if($outfit == null)
            return response()->json(['message' => 'El producto no existe'],404);

        return response()->json(['outfit' => $outfit],200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $outfit = Outfit::find($id);

        if($outfit == null)
            return response()->json(['message' => 'El outfit no existe'],404);

        if($outfit->status == 0){$outfit->status = 1;}else{$outfit->status = 0;}
        $outfit->save();

        return response()->json(['message' => 'Se cambio el estado del outfit'],200);
    }
}
