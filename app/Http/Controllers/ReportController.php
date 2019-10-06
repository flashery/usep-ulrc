<?php

namespace App\Http\Controllers;

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

                $reports = $this->collectionPerCollege($request->all());

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

    private function collectionNotUsed()
    {
        $bibs = [];

        $bibs = $this->getBibs();

        if (sizeof($bibs) === 0) return [];

        $Dewey_decimals_ranges = $this->generateRanges();
        $reports = [];

        $departments = Department::all();

        foreach ($Dewey_decimals_ranges as $range) {

            $start = (int)  $range['start'];
            $end = (int) $range['end'];

            $views = [];
            $data = [];
            foreach ($bibs as $bib) {


                // Get call number value
                $call_number = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');

                // Get the Dewey decimal by extracting the first three characters of the Call Number value
                $Dewey_decimal = $this->getDeweyDecimal($call_number);

                // Compare value if the same as the current index number
                if ($Dewey_decimal >= $start && $Dewey_decimal <= $end) {


                    foreach ($departments as $department) {

                        $department_ids = [];

                        foreach ($bib->subjects as $subject) {

                            $subject_department = $subject->course->department;

                            if (in_array($department->id, $department_ids) && $department->id === $subject_department->id) {

                                $data[$department->name] += sizeof($bib->volumes);
                            } else {

                                $data[$department->name] = 0;

                                array_push($department_ids, $department->id);
                            }
                        }
                    }
                }


                array_push($views, $data);
            }


            $data_range = $range['start'] . '-' . $range['end'];
            $key_value =  [
                $data_range => $data
            ];
            array_push($reports, $key_value);
        }

        return $reports;
    }

    public function export(Request $request)
    {
        $url = '';

        if ($request->get('type') === 'collection_per_college') {

            $reports = $this->collectionPerCollege($request->all());

        } else if ($request->get('type') === 'by_date_of_pub') {

            $by_date_pub = new ByDateOfPublication($request->all());
            $url = $by_date_pub->createChart();

        } else if ($request->get('type') === 'all_collection_per_college') {

            $all_collection_per_college_repo = new AllCollectionPerCollegeRepository($request->all());
            $url = $all_collection_per_college_repo->createChart();

        } else if ($request->get('type') === 'all_collection_not_used') {

            $reports = $this->collectionNotUsed();

        } else {

            $all_collection_repo = new AllCollectionRepository($request->all());
            $url = $all_collection_repo->createChart();

        }

        return response()->json($url);
    }
}
