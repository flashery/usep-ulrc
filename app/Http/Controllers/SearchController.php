<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bib;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($request->ajax()) {

            $bibs = Bib::with('marc_tags', 'volumes')
                ->with(['subjects' => function ($q) use ($keyword) {
                    $q->where('code', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('name', 'LIKE', '%' . $keyword . '%');
                }])
                ->whereHas('subjects', function ($q) use ($keyword) {
                    $q->where('code', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('name', 'LIKE', '%' . $keyword . '%');
                })->orWhereHas('marc_tags',  function ($q) use ($keyword) {
                    $q->where('value', 'LIKE', '%' . $keyword . '%');
                })->get();

            return response()->json($bibs);
        }

        return view('search');
    }

    public function updateBibViews(Request $request)
    {
        $bib = Bib::find($request->get('id'));
        $bib->views += 1;
        $bib->save();
    }
}
