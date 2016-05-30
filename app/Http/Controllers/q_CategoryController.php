<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Questionnaire\Category;
use Illuminate\Http\Request;

use Dashboard\Http\Requests;

class q_CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories],200);
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
                'name'  =>  'required|unique:categories'
            ];

            if(\Validator::make($request->all(), $rules)->fails())
                return response()->json(['message' =>  'El nombre de categoria no es valido o ya existe'],401);

            $category = new Category();
            $category->name = ucfirst($request->input('name'));
            $category->save();

            return response()->json(['message' => 'La categoria se agrego correctamente'],200);
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
        $category = Category::find($id);
        return response()->json(['category' => $category],200);
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
        try{
            $rules = [
                'name'  =>  'required'
            ];

            if(\Validator::make($request->all(), $rules)->fails())
                return response()->json(['messages' => 'El nombre de categoria no es valido'],401);

            if(Category::where('name','=',$request->input('name'))->where('id','<>',$id)->count() > 0)
                return response()->json(['message' => 'La categoria ya existe'],401);

            $category = Category::find($id);
            if(!$category->exists())
                return response()->json(['message' => 'La categoria a editar no existe'],401);

            $category->name = ucfirst($request->input('name'));
            $category->save();

            return response()->json(['message' => 'Se edito con exito la categoria'],200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Ocurrio un problema inesperado'],500);
        }
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
