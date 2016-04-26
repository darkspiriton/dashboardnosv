<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Models\Kardex\Type_Attribute;
use Illuminate\Http\Request;
use Dashboard\Http\Requests;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types=Type_Attribute::all();

        foreach ($types as $type) {
            $type->att;
        }
        return response()->json(['types' => $types],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attributes =  DB::table('attributes_kardexs')
                            ->select(array('attributes_kardexs.id','types_attributes.name', 'attributes.valor'))
                            ->join('attributes', function ($join) {
                                    $join->on('attributes_kardexs.attribute_id', '=', 'attributes.id');
                                })
                            ->join('types_attributes', function ($join) {
                                $join->on('attributes.type_id', '=', 'types_attributes.id');
                            })
                            ->where('group_attribute_id',$id)
                            ->get();
        return response()->json(['attributes' => $attributes],200);
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
