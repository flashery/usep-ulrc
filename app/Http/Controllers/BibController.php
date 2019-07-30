<?php

namespace App\Http\Controllers;

use App\Bib;
use Illuminate\Http\Request;

class BibController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bibs = Bib::with('marc_tags')->get();

        if ($request->ajax()) {
            return response()->json($bibs);
        }

        return view('bibs');
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
        $bib = Bib::create();

        $bib_tags = $request->get('bib_tags');

        foreach ($bib_tags as $index => $bib_tag) {
            if (!$bib_tag) unset($bib_tags[$index]);
        }

        $bib->marc_tags()->sync($bib_tags);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function show(Bib $bib)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function edit(Bib $bib)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bib $bib)
    {
        $bib_tags = $request->get('bib_tags');
        
        foreach ($bib_tags as $index => $bib_tag) {
            if (!$bib_tag) unset($bib_tags[$index]);
        }

        $bib->marc_tags()->sync($bib_tags);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bib $bib)
    {
        $bib->delete();
    }
}
