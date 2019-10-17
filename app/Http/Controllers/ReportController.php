<?php

namespace App\Http\Controllers;

use App\Bib;
use Illuminate\Http\Request;
use App\Department;
use App\Repositories\Reports\AllCollectionNotUsed;
use App\Repositories\Reports\AllCollectionPerCollegeRepository;
use App\Repositories\Reports\AllCollectionRepository;
use App\Repositories\Reports\ByDateOfPublication;

class ReportController extends Controller
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

    public function index(Request $request)
    {
        $reports = [];

        if ($request->ajax()) {
            if ($request->get('type') === 'collection_per_college') {
                $by_date_pub = new ByDateOfPublication($request->all());
                $reports = $by_date_pub->getReports();
            } else if ($request->get('type') === 'by_date_of_pub') {

                $by_date_pub = new ByDateOfPublication($request->all());
                $reports = $by_date_pub->getReports();
            } else if ($request->get('type') === 'all_collection_not_used') {

                $all_collection_not_used = new AllCollectionNotUsed($request->all());
                $reports = $all_collection_not_used->getReports();
            } else if ($request->get('type') === 'all_collection_per_college') {

                $all_collection_per_college_repo = new AllCollectionPerCollegeRepository($request->all());
                $reports = $all_collection_per_college_repo->getReports();
            } else {

                $all_collection_repo = new AllCollectionRepository($request->all());
                $reports = $all_collection_repo->getReports();
            }

            return response()->json(['reports' => $reports]);
        }

        return view('reports');
    }

    public function export(Request $request)
    {
        $url = '';

        if ($request->get('type') === 'collection_per_college') {

            $by_date_pub = new ByDateOfPublication($request->all());
            $url = $by_date_pub->createChart();

        } else if ($request->get('type') === 'by_date_of_pub') {

            $by_date_pub = new ByDateOfPublication($request->all());
            $url = $by_date_pub->createChart();
        } else if ($request->get('type') === 'all_collection_per_college') {

            $all_collection_per_college_repo = new AllCollectionPerCollegeRepository($request->all());
            $url = $all_collection_per_college_repo->createChart();
        } else if ($request->get('type') === 'all_collection_not_used') {

            $all_collection_not_used = new AllCollectionNotUsed($request->all());
            $url = $all_collection_not_used->createChart();
        } else {

            $all_collection_repo = new AllCollectionRepository($request->all());
            $url = $all_collection_repo->createChart();
        }

        return response()->json($url);
    }
}
