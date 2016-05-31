<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Questionnaire\Questionnaire;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class QuestionnairesController extends Controller
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
        $questionnaires = Questionnaire::with('category')->get();
        return response()->json(['questionnaires' => $questionnaires],200);
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
        try{
            $rules = [
                'description'   =>  'required|string|max:255',
                'category_id'   =>  'required|integer|exists:categories,id',
                'questions'     =>  'required|array',
                'questions.*.id'    =>  'required|integer|exists:questions,id'
            ];

            if(\Validator::make($request->all(), $rules)->fails())
                return response()->json(['message' => 'La informacion enviada no es valida'],401);

            $questionnaire = new Questionnaire();
            $questionnaire->description = $request->input('description');
            $questionnaire->category_id = $request->input('category_id');
            $questionnaire->save();

            $questions = array();
            foreach ($request->input('questions') as $key => $question){
                $questions[$key]['question_id'] = $question['id'];
            }

            $questionnaire->questions()->attach($questions);

            return response()->json(['message' => 'Se guardo el cuestionario correctamente'],200);
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
        $questionnaire = Questionnaire::with('category','questions')->find($id);
        if($questionnaire == null)
            return response()->json(['message' => 'El cuestionario no existe'],404);

        return response()->json(['questionnaire' => $questionnaire],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $questionnaire = Questionnaire::find($id);
            if($questionnaire == null)
                return response()->json(['message' => 'El cuestionario no existe'],404);

            $questions = \DB::table('questionnaires_questions as qq')->select(array('qq.question_id as id','q.question'))
                        ->join('questions as q','q.id','=','qq.question_id')
                        ->where('qq.questionnaire_id','=',$id)
                        ->get();

            return response()->json(['questionnaire' => $questionnaire, 'questions' => $questions],200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Ocurrio un problema inesperado'],500);
        }
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

    public function QuestionnaireForCategory($id){
        $questionnaire = Questionnaire::with('questions')
                                ->where('category_id','=',$id)
                                ->orderBy('created_at','desc')
                                ->first();

        if($questionnaire == null)
            return response()->json(['message' => 'No hay cuestionarios asociados a esta categoria'],404);

        return response()->json(['questionnaire' => $questionnaire],200);
    }
}
