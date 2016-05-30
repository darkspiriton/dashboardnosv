<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Questionnaire\Option;
use Dashboard\Models\Questionnaire\Question;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class QuestionController extends Controller
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
        $questions = Question::all();
        return response()->json(['questions' => $questions],200);
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
            'question'  => 'required|string|max:255',
            'options'   =>  'required|array',
            'options.*' =>  'string'
        ];

        if(\Validator::make($request->all(), $rules)->fails())
            return response()->json(['message' => 'Campos requeridos no recibidos'],401);

        $question = new Question();
        $question->question = '¿ '.ucfirst($request->input('question')).' ?';
        $question->save();

        $options = array();
        foreach ($request->input('options') as $option){
            $optionModel = new Option();
            $optionModel->option = $option;
            array_push($options, $optionModel);
        }

        $question->options()->saveMany($options);

        return response()->json(['message' => 'Se agrego la pregunta'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::with('options')->find($id);
        return response()->json(['question' => $question],200);
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
