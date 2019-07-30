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

            $bibs = Bib::with('marc_tags')->whereHas('marc_tags',  function ($q) use ($keyword) {
                $q->where('value', 'LIKE', '%' . $keyword . '%');
            })->get();

            return response()->json($bibs);
        }

        return view('search');
    }
}
