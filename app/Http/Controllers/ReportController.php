<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bib;
use App\Department;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            if ($request->get('type') === 'collection_per_college') {
                $reports = $this->collectionPerCollege($request->all());
            } else if ($request->get('type') === 'collection_each_year') {
                $reports = $this->collectionEachYear($request->all());
            } else {
                $reports = $this->generalReports($request->all());
            }

            return response()->json($reports);
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
                $value = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 114);

                // Get the deway decimal by extracting the first three characters of the Call Number value
                $deway_decimal = (int) substr($value, 0, 3);

                // Compare value if the same as the current index number
                if ($deway_decimal >= $start && $deway_decimal <= $end) {

                    // Get the number of volumes of this bib
                    $volumes += $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 113);
                    $no_of_titles += $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 115);
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

        $deway_decimals_ranges = $this->generateRanges();
        $reports = [];

        $volumes = 0;

        foreach ($deway_decimals_ranges as $range) {
            $start = (int)  $range['start'];
            $end = (int) $range['end'];

            $volumes = 0;

            foreach ($bibs as $bib) {
                // Get call number value
                $call_number = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 114);

                // Get the deway decimal by extracting the first three characters of the Call Number value
                $deway_decimal = $this->getDewayDecimal($call_number);

                // Compare value if the same as the current index number
                if ($deway_decimal >= $start && $deway_decimal <= $end) {

                    // Get the number of volumes of this bib
                    $volumes += $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 113);
                }
            }
            $data_range = $range['start'] . '-' . $range['end'];
            $key_value =  [
                $data_range => [
                    'volumes' => $volumes,
                ]
            ];
            array_push($reports, $key_value);
        }

        return $reports;
    }

    private function collectionEachYear($request_data)
    {
        $bibs = [];

        $bibs = $this->getBibs($request_data);

        if (sizeof($bibs) === 0) return [];

        $reports = [];
        foreach ($bibs as $bib) {
            $date_of_publication = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 116);
            $year = Carbon::parse($date_of_publication)->format('Y');
            $volume = (int) $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 113);

            if (!array_key_exists($year, $reports)) {
                $reports[$year] = ['volumes' => $volume];
                // array_push($reports, $key_value);
            } else {
                $last_volume = 0;
                $last_volume = $reports[$year]['volumes'];
                $reports[$year]['volumes'] = $last_volume;
                $reports[$year]['volumes'] = $last_volume + $volume;
            }
        }
        // sorts reportss in ascending order, according to the key
        ksort($reports);

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

    private function getBibs($request_data)
    {
        if (isset($request_data['department_id'])) {
            $department = Department::whereId($request_data['department_id'])->with('subjects')->first();
            $subject_ids = [];
            foreach ($department->subjects as $subject) {
                array_push($subject_ids, $subject->id);
            }
            return Bib::with('marc_tags')->whereHas('subjects', function ($q) use ($subject_ids) {
                $q->whereIn('subject_id', $subject_ids);
            })->get();
        }

        return Bib::with('marc_tags')->get();
    }

    private function getSpecificMarcTag(array $marc_tags, $marc_tag)
    {
        foreach ($marc_tags as $marc) {
            if ($marc['marc_tag'] === $marc_tag) return $marc['pivot']['value'];
        }
    }

    private function getDewayDecimal($call_number)
    {
        return (int) substr($call_number, 0, 3);
    }
}
