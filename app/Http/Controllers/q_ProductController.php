<?php

namespace Dashboard\Http\Controllers;


use Dashboard\Models\Questionnaire\AnswerProduct;
use Dashboard\Models\Questionnaire\Product;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class q_ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:GOD,NOS');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json(['products' => $products],200);
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
            'name'          =>  'required|string|max:255',
            'category_id'   =>  'required|integer|exists:categories,id',
            'questionnaire_id'   =>  'required|integer|exists:questionnaires,id',
            'responses'     =>  'required|array',
            'responses.*.id'     => 'required|integer|exists:options,id'
        ];

        if(\Validator::make($request->all(), $rules)->fails())
            return response()->json(['message' => 'Valores recibidos no son validos'],401);

        $product = new Product();
        $product->name = ucwords(strtolower($request->input('name')));
        $product->category_id = $request->input('category_id');
        $product->save();

        $answers = array();
        foreach ($request->input('responses') as $key => $value){
            $answer = new AnswerProduct();
            $answer->option_id = $value['id'];
            $answer->questionnaire_id = $request->input('questionnaire_id');
            $answers[$key] = $answer;
        }

        $product->answers()->saveMany($answers);

        return response()->json(['message' => 'El prodcuto se registro correctamente'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = Product::with(['answers' => function($query){
            return $query->with(['option' => function($query){
                return $query->with('question');
            }]);
        },'category'])->find($id);

        if($products == null)
            return response()->json(['message' => 'El producto no eciste'],404);

        return response()->json(['product' => $products],200);
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
        //
    }
}
