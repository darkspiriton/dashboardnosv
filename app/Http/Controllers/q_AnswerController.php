<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Questionnaire\AnswerCustomer;
use Dashboard\Models\Questionnaire\Customer;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class q_AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responses = AnswerCustomer::with('customer','questionnaire')->groupBy('customer_id','questionnaire_id')->get();

        return response()->json(['responses' => $responses],200);
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
            'customer_id'   =>  'integer|exists:aux2customer,id',
            'category_id'   =>  'required|integer|exists:categories,id',
            'questionnaire_id'   =>  'required|integer|exists:questionnaires,id',
            'responses'     =>  'required|array',
            'responses.*.id'     => 'required|integer|exists:options,id'
        ];

        if(\Validator::make($request->all(), $rules)->fails())
            return response()->json(['message' => 'Valores recibidos no son validos'],401);

        if($request->input('customer_id')){
            foreach ($request->input('responses') as $response){
                $responseModel = new AnswerCustomer();
                $responseModel->customer_id = $request->input('customer_id');
                $responseModel->questionnaire_id = $request->input('questionnaire_id');
                $responseModel->option_id = $response->id;
                $responseModel->save();
            }
        } else {
            $rules = [
                'url'  =>  'required|string|max:255',
                'name'  =>  'required|string|max:255',
                'phone'   =>  'required|string|max:255s',
            ];

            if(\Validator::make($request->all(), $rules)->fails())
                return response()->json(['message' => 'Valores recibidos no son validos'],401);

            $customer = new Customer();
            $customer->url = $request->input('url');
            $customer->name = $request->input('name');
            $customer->phone = $request->input('phone');
            $customer->save();

            foreach ($request->input('responses') as $response){
                $responseModel = new AnswerCustomer();
                $responseModel->customer_id = $customer->id;
                $responseModel->questionnaire_id = $request->input('questionnaire_id');
                $responseModel->option_id = $response['id'];
                $responseModel->save();
            }

        }

        return response()->json(['message' => 'El cuestionario se registro correctamente'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $qq)
    {
        $customer = Customer::with(['answers' => function($query) use ($qq){
            return $query->with(['option' => function($query){
                return $query->with('question');
            }])->where('questionnaire_id','=',$qq)->first();
        }])->find($id);

        if($customer == null)
            return response()->json(['message' => 'El cliente no eciste'],404);

        return response()->json(['customer' => $customer],200);
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
