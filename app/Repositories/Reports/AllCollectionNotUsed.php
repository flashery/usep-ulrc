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
    protected const TITLE = 'All Collection Not Used';

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


        $Dewey_decimals_ranges = $this->generateRanges();
        $departments = Department::all();


        foreach ($departments as $department) {
            $data = [];
            $data_ranges = [];

            foreach ($Dewey_decimals_ranges as $range) {

                $start = (int)  $range['start'];
                $end = (int) $range['end'];
                $data_range = $range['start'] . '-' . $range['end'];

                foreach ($bibs as $bib) {

                    // Get call number value
                    $call_number = $bib_repo->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');

                    // Get the Dewey decimal by extracting the first three characters of the Call Number value
                    $Dewey_decimal = $bib_repo->getDeweyDecimal($call_number);

                    foreach ($bib->subjects as $subject) {
                        $subject_department = $subject->course->department;

                        // Compare value if the same as the current index number
                        if ($Dewey_decimal >= $start && $Dewey_decimal <= $end) {

                            if (in_array($data_range, $data_ranges)) {
                                if ($bib->views <= 0 && $department->id === $subject_department->id)
                                    $data[$data_range] += 1;
                            } else {
                                if ($bib->views <= 0 && $department->id === $subject_department->id) {
                                    $data[$data_range] = 1;
                                } else {
                                    $data[$data_range] = 0;
                                }
                                array_push($data_ranges, $data_range);
                            }
                        } else {
                            if (!in_array($data_range, $data_ranges)) {
                                $data[$data_range] = 0;
                                array_push($data_ranges, $data_range);
                            }
                        }
                    }
                }
            }

            array_push($reports, $data);
        }

        $labels = ['Dewey Decimal'];
        foreach ($departments as $department) {
            array_push($labels, $department->name);
        }

        array_unshift($reports, $labels);

        return $reports;
    }

    public function createChart()
    {
        $data = $this->getReports();

        if (sizeof($data) <= 0) return true;

        $labels = $data[0];
        unset($data[0]);
        $datas = [];
        $row_keys = [];
        foreach ($data as $cols) {

            foreach ($cols as $key => $col) {
                array_push($row_keys, [$key]);
            }
            break;
        }
        foreach ($row_keys as $row_key) {

            foreach ($data as $cols) {

                foreach ($cols as $key => $col) {

                    if ($row_key[0] === $key)
                        array_push($row_key, $col);
                }
            }
            array_push($datas, $row_key);
        }
        array_unshift($datas, $labels);
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $this->insertLogo($worksheet, self::TITLE);

        $worksheet->fromArray($datas, null, 'A10');
        // Set the Labels for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesLabels =  [];
        for ($i = 0; $i < sizeof($labels); $i++) {
            $cell_col = self::CELL_COLS[$i];
            array_push($dataSeriesLabels,  new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$' . $cell_col . '$10', null, 1));
        }
        // $dataSeriesLabels = [
        //     new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$10', null, 1),
        //     new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$10', null, 1),

        // ];

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
        $dataSeriesValues =  [];
        for ($i = 0; $i < sizeof($labels); $i++) {
            $cell_col = self::CELL_COLS[$i];
            array_push($dataSeriesValues,  new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$' . $cell_col . '$11:$' . $cell_col . '$20', null, 4));
        }
        // $dataSeriesValues = [
        //     new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$11:$B$20', null, 4), // Volumes value
        //     new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$11:$C$20', null, 4), // No. of Titles value
        // ];

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
        $title = new Title(self::TITLE);
        // $yAxisLabel = new Title('Volumes');
        $yAxisLabel = null;
        $xAxisLabel = new Title('Dewey Decimal');

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

        $topLeftPosNum = sizeof($datas) + 12;
        $bottomRightPostNum = sizeof($datas) + 25;
        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A' . $topLeftPosNum);
        $chart->setBottomRightPosition('I' . $bottomRightPostNum);
        $worksheet->getStyle(self::CELL_COLS[0] . '10:' . self::CELL_COLS[sizeof($labels) - 1] . '10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f86060');
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
