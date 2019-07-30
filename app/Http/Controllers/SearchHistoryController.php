<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SearchHistory;
use Illuminate\Support\Facades\Auth;

class SearchHistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_histories = SearchHistory::where('user_id', Auth::user()->id)
            ->limit(5)
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json($search_histories);
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
        SearchHistory::create([
            'user_id' => Auth::user()->id,
            'keywords' => $request->get('keywords'),
        ]);

        $search_histories = SearchHistory::where('user_id', Auth::user()->id)
            ->limit(5)
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json($search_histories);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function show(SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SearchHistory $searchHistory)
    {
        //
    }
}
