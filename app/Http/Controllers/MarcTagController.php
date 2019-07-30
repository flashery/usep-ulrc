<?php

namespace App\Http\Controllers;

use App\MarcTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcTagController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marc_tags = MarcTag::all();
        // dd($marc_tags);
        return response()->json($marc_tags);
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

        // $validatedData = $request->validate([
        //     'marc_tag' => ['required|unique:marc_tags' => 'This marc_tag already exist.'],
        //     'non_marc_tag' => 'required',
        // ]);

        $validator = Validator::make(
            $request->all(),
            [
                'marc_tag' => 'required|unique:marc_tags',
                'non_marc_tag' => 'required|unique:marc_tags',
            ],
            [
                'marc_tag.unique' => 'Sorry, this :attribute already exists in our records.',
                'non_marc_tag.unique' => 'Sorry, this :attribute already exists in our records.',
            ]
        )->validate();

        $marc_tag = MarcTag::create($request->all());

        return response()->json($marc_tag);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MarcTag  $marcTag
     * @return \Illuminate\Http\Response
     */
    public function show(MarcTag $marcTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MarcTag  $marcTag
     * @return \Illuminate\Http\Response
     */
    public function edit(MarcTag $marcTag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MarcTag  $marcTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarcTag $marcTag)
    {
        $success = $marcTag->update($request->all());

        return response()->json($success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MarcTag  $marcTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarcTag $marcTag)
    {
        $marcTag->delete();
    }
}
