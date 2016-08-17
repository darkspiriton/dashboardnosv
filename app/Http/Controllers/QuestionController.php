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
        $this->middleware('auth:GOD,NOS');
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
        try{
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

            $optionModel = new Option();
            $optionModel->option = 'Ninguno';
            array_push($options, $optionModel);
            foreach ($request->input('options') as $option){
                $optionModel = new Option();
                $optionModel->option = $option;
                array_push($options, $optionModel);
            }

            $question->options()->saveMany($options);

            return response()->json(['message' => 'Se agrego la pregunta'],200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Ocurrio un problema inesperado'],500);
        }
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

        if(!$question->exists())
            return response()->json(['message' => 'la pregunta a mostrar no existe'],404);

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

    public function OptionsForQuestion($id){
        $options = Option::where('question_id','=',$id)->get();

        if($options->count() == 0)
            return response()->json(['message' => 'La pregunta no tiene respuestas o no existe'],404);

        return response()->json(['options' => $options],200);
    }
}
