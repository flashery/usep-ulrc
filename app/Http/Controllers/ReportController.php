<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bib;
use App\Course;
use App\Department;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = [];
        $per_year = [];
        $not_used = [];

        if ($request->ajax()) {

            if ($request->get('type') === 'collection_per_college') {
                $reports = $this->collectionPerCollege($request->all());
            } else if ($request->get('type') === 'collection_per_year') {
                $reports = $this->collectionPerYear($request->all());
            } else if ($request->get('type') === 'all_collection_per_college') {
                $reports = $this->allCollectionPerCollege($request->all());
            } else {
                $reports = $this->generalReports($request->all());

                $not_used = $this->collectionNotUsed();
            }

            return response()->json(['reports' => $reports, 'not_used' => $not_used]);
        }

        return view('reports');
    }

    private function generalReports($request_data)
    {
        $bibs = [];

        $bibs = $this->getBibs($request_data);

        if (sizeof($bibs) === 0) return [];

        $deway_decimals_ranges = $this->generateRanges();
        $reports = [];

        $volumes = 0;
        $no_of_titles = 0;

        foreach ($deway_decimals_ranges as $range) {
            $start = (int)  $range['start'];
            $end = (int) $range['end'];

            $volumes = 0;
            $no_of_titles = 0;
            foreach ($bibs as $bib) {
                // Get call number value
                $call_number = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');

                // Get the deway decimal by extracting the first three characters of the Call Number value
                $deway_decimal =  $this->getDewayDecimal($call_number);

                // Compare value if the same as the current index number
                if ($deway_decimal >= $start && $deway_decimal <= $end) {

                    // Get the number of volumes of this bib
                    $volumes += sizeof($bib->volumes);
                    $no_of_titles++;
                }
            }
            $data_range = $range['start'] . '-' . $range['end'];
            $key_value =  [
                $data_range => [
                    'volumes' => $volumes,
                    'no_of_titles' => $no_of_titles
                ]
            ];
            array_push($reports, $key_value);
        }

        return $reports;
    }

    private function collectionPerCollege($request_data)
    {
        $bibs = [];

        $bibs = $this->getBibs($request_data);

        if (sizeof($bibs) === 0) return [];

        $reports = [];
        foreach ($bibs as $bib) {

            $call_number = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');
            $date_of_publication = $this->extractDateOfPub($call_number);
            $volume =  sizeof($bib->volumes);
            $no_of_titles = 1;

            if (!array_key_exists($date_of_publication, $reports)) {
                $reports[$date_of_publication]['volumes'] = $volume;
                $reports[$date_of_publication]['no_of_titles'] = $no_of_titles;
            } else {
                $last_volume = 0;
                $last_volume = $reports[$date_of_publication]['volumes'];
                $reports[$date_of_publication]['volumes'] = $last_volume;
                $reports[$date_of_publication]['volumes'] = $last_volume + $volume;

                $last_no_of_titles = 0;
                $last_no_of_titles = $reports[$date_of_publication]['no_of_titles'];
                $reports[$date_of_publication]['no_of_titles'] = $last_no_of_titles;
                $reports[$date_of_publication]['no_of_titles'] = $last_no_of_titles + $no_of_titles;
            }
        }
        // sorts reportss in ascending order, base on the key
        ksort($reports);

        return $reports;
    }

    private function collectionPerYear($request_data)
    {
        $bibs = [];

        $bibs = $this->getBibs($request_data);

        if (sizeof($bibs) === 0) return [];

        $reports = [];
        foreach ($bibs as $bib) {

            $call_number = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');
            $date_of_publication = $this->extractDateOfPub($call_number);
            $volume =  sizeof($bib->volumes);
            $no_of_titles = 1;

            if (!array_key_exists($date_of_publication, $reports)) {
                $reports[$date_of_publication]['volumes'] = $volume;
                $reports[$date_of_publication]['no_of_titles'] = $no_of_titles;
            } else {
                $last_volume = 0;
                $last_volume = $reports[$date_of_publication]['volumes'];
                $reports[$date_of_publication]['volumes'] = $last_volume;
                $reports[$date_of_publication]['volumes'] = $last_volume + $volume;

                $last_no_of_titles = 0;
                $last_no_of_titles = $reports[$date_of_publication]['no_of_titles'];
                $reports[$date_of_publication]['no_of_titles'] = $last_no_of_titles;
                $reports[$date_of_publication]['no_of_titles'] = $last_no_of_titles + $no_of_titles;
            }
        }
        // sorts reportss in ascending order, base on the key
        ksort($reports);

        return $reports;
    }

    private function allCollectionPerCollege($request_data)
    {
        $course_id = $request_data['course_id'];

        $course = Course::with('subjects.bibs')->where('id', $course_id)->first();
        $subject_ids = [];
        foreach ($course->subjects as $subject) {
            array_push($subject_ids, $subject->id);
        }

        if (sizeof($course->subjects) === 0) return [];

        $reports = [];

        foreach ($course->subjects as $subject) {
            foreach ($subject->bibs as $bib) {
                $volume =  sizeof($bib->volumes);
                $no_of_titles = 1;

                if (!array_key_exists($subject->name, $reports)) {
                    $reports[$subject->name]['volumes'] = $volume;
                    $reports[$subject->name]['no_of_titles'] = $no_of_titles;
                } else {
                    $last_volume = 0;
                    $last_volume = $reports[$subject->name]['volumes'];
                    $reports[$subject->name]['volumes'] = $last_volume;
                    $reports[$subject->name]['volumes'] = $last_volume + $volume;

                    $last_no_of_titles = 0;
                    $last_no_of_titles = $reports[$subject->name]['no_of_titles'];
                    $reports[$subject->name]['no_of_titles'] = $last_no_of_titles;
                    $reports[$subject->name]['no_of_titles'] = $last_no_of_titles + $no_of_titles;
                }
            }
        }
        // sorts reportss in ascending order, base on the key
        ksort($reports);

        return $reports;
    }


    private function collectionNotUsed()
    {
        $bibs = [];

        $bibs = $this->getBibs();

        if (sizeof($bibs) === 0) return [];

        $deway_decimals_ranges = $this->generateRanges();
        $reports = [];

        $departments = Department::all();

        foreach ($deway_decimals_ranges as $range) {

            $start = (int)  $range['start'];
            $end = (int) $range['end'];

            $views = [];
            $data = [];
            foreach ($bibs as $bib) {


                // Get call number value
                $call_number = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');

                // Get the deway decimal by extracting the first three characters of the Call Number value
                $deway_decimal = $this->getDewayDecimal($call_number);

                // Compare value if the same as the current index number
                if ($deway_decimal >= $start && $deway_decimal <= $end) {


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

    private function generateRanges()
    {
        $data = [];
        $deway_decimal_start = 0;
        $deway_decimal_end = 100;
        $start = 0;
        $step = 100;
        $end = 1000;

        foreach (range($start, $end, $step) as $number) {

            if ($number >= $end) break;

            $deway_decimal_start =  str_pad($number, 3, '0', STR_PAD_LEFT); // add 0s to the left of the digits if digits lesser than 3
            $deway_decimal_end = str_pad($number + $step  - 1, 3, '0', STR_PAD_LEFT); // add 0s to the left of the digits if digits lesser than 3

            array_push($data, ['start' => $deway_decimal_start, 'end' => $deway_decimal_end]);
        }

        return $data;
    }

    private function getBibs($request_data = null)
    {
        if (isset($request_data['department_id'])) {
            $department = Department::whereId($request_data['department_id'])->with('subjects')->first();
            $subject_ids = [];
            foreach ($department->subjects as $subject) {
                array_push($subject_ids, $subject->id);
            }
            return Bib::with('marc_tags', 'subjects', 'volumes')->whereHas('subjects', function ($q) use ($subject_ids) {
                $q->whereIn('subject_id', $subject_ids);
            })->get();
        }

        return Bib::with('marc_tags', 'subjects', 'volumes')->get();
    }

    private function getSpecificMarcTag(array $marc_tags, $marc_tag)
    {
        foreach ($marc_tags as $marc) {
            if ($marc['marc_tag'] === $marc_tag) return $marc['pivot']['value'];
        }
    }

    private function extractDateOfPub($call_number)
    {
        return substr($call_number, -4, strlen($call_number));
    }

    private function getDewayDecimal($call_number)
    {
        return (int) substr($call_number, 5, 3);
    }
}
