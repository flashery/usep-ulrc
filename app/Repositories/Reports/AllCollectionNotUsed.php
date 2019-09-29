<?php

namespace App\Repositories\Reports;

use App\Course;
use App\Department;
use App\Repositories\BibRepository;
use App\Repositories\ReportsRepository;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AllCollectionNotUsed extends ReportsRepository
{
    protected $report_path = '';
    private $data = [];

    public function __construct(array $data)
    {
        $this->report_path = public_path() . '/downloadable';
        $this->data = $data;
    }

    public function getReports()
    {
        $bibs = [];

        $bib_repo = new  BibRepository();

        $bibs = $bib_repo->getBibs();

        if (sizeof($bibs) === 0) return [];

        $reports = [];



        foreach ($bibs as $bib) {


            // Get call number value
            $call_number = $bib_repo->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');

            // Get the deway decimal by extracting the first three characters of the Call Number value
            $deway_decimal = $bib_repo->getDewayDecimal($call_number);
            $departments = Department::all();

            $data = [];
            $row_data = [];
            $department_ids = [];

            foreach ($departments as $department) {


                foreach ($bib->subjects as $subject) {


                    $deway_decimals_ranges = $this->generateRanges();

                    foreach ($deway_decimals_ranges as $range) {


                        $start = (int)  $range['start'];
                        $end = (int) $range['end'];
                        $data_range = $range['start'] . '-' . $range['end'];

                        // Compare value if the same as the current index number
                        if ($deway_decimal >= $start && $deway_decimal <= $end) {
                            $subject_department = $subject->course->department;

                            if (in_array($department->id, $department_ids) && $department->id === $subject_department->id) {

                                $data[$data_range] += sizeof($bib->volumes);
                            } else {

                                $data[$data_range] = 0;

                                array_push($department_ids, $department->id);
                            }
                        }
                    }
                }
            }

            dump($data);



            // array_push($row_data, $data_range);
            // if (sizeof($data) > 0) {
            //     foreach ($data as $key => $dep) {

            //         array_push($row_data, $dep);
            //     }
            // } else {
            //     foreach ($departments as $department) {
            //         array_push($row_data, 0);
            //     }
            // }
            // array_push($reports, $row_data);
        }

        $labels = ['Deway Decimal'];
        foreach ($departments as $department) {
            array_push($labels, $department->name);
        }
        array_unshift($reports, $labels);
        return $reports;
    }

    public function createChart()
    {
        $data = $this->getReports();

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $this->insertLogo($worksheet);

        $worksheet->fromArray($data, null, 'A10');
        // Set the Labels for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$10', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$10', null, 1),

        ];
        // Set the X-Axis Labels
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$11:$A$20', null, 4)
        ];
        // Set the Data values for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$11:$B$20', null, 4), // Volumes value
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$11:$C$20', null, 4), // No. of Titles value
        ];

        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );
        // Set additional dataseries parameters
        //     Make it a horizontal bar rather than a vertical column graph
        $series->setPlotDirection(DataSeries::DIRECTION_VERTICAL);
        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('All Collection');
        // $yAxisLabel = new Title('Volumes');
        $yAxisLabel = null;
        $xAxisLabel = new Title('Deway Decimal');

        // Create the chart
        $chart = new Chart(
            'General Reports', // name
            $title, // title
            $legend, // legendd
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            $xAxisLabel, // xAxisLabel
            $yAxisLabel  // yAxisLabel
        );

        $topLeftPosNum = sizeof($data) + 12;
        $bottomRightPostNum = sizeof($data) + 25;
        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A' . $topLeftPosNum);
        $chart->setBottomRightPosition('I' . $bottomRightPostNum);
        $worksheet->getStyle('A10:C10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f86060');
        $worksheet->getColumnDimension('A')->setAutoSize(true);
        // Add the chart to the worksheet
        $worksheet->addChart($chart);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $file_name = 'report-' . uniqid() . '.xlsx';

        $writer->save($this->report_path . '/' . $file_name);
        return  '/downloadable' . '/' . $file_name;
    }
}
