<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bib;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $reports = $this->generalReports();

            return response()->json($reports);
        }

        return view('reports');
    }

    private function generalReports()
    {
        $bibs = Bib::with('marc_tags')->get();
        $deway_decimals_ranges = $this->generateRanges();
        $reports = [];

        $volume = 0;
        foreach ($deway_decimals_ranges as $range) {
            $start = (int)  $range['start'];
            $end = (int) $range['end'];

            $volume = 0;

            foreach ($bibs as $bib) {
                // Get call number value
                $value = $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 'Call Number');

                // Get the deway decimal by extracting the first three characters of the Call Number value
                $deway_decimal = (int) substr($value, 0, 3);

                // Compare value if the same as the current index number
                if ($deway_decimal >= $start && $deway_decimal <= $end) {

                    // Get the number of volumes of this bib
                    $volume += $this->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), 'Volume');
                }
            }
            $data_range = $range['start'] . '-' . $range['end'];
            $key_value =  [$data_range => $volume];
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

    public function getSpecificMarcTag(array $marc_tags, $non_marc_tag)
    {
        foreach ($marc_tags as $marc) {
            if ($marc['non_marc_tag'] === $non_marc_tag) return $marc['pivot']['value'];
        }
    }
}
