<?php

namespace App\Repositories\Reports;

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

class ByDateOfPublication extends ReportsRepository
{
    protected $report_path = '';
    private $data = [];
    protected const TITLE = 'Collection by Date of Publication';

    public function __construct(array $data)
    {
        $this->report_path = public_path() . '/downloadable';
        $this->data = $data;
    }

    public function getReports()
    {
        $bibs = [];
        
        $bib_repo = new BibRepository();

        $bibs = $bib_repo->getBibs($this->data);

        if (sizeof($bibs) === 0) return [];

        $reports = [];
        foreach ($bibs as $bib) {

            $call_number = $bib_repo->getSpecificMarcTag(collect($bib->marc_tags)->toArray(), '082');
            $date_of_publication = $bib_repo->extractDateOfPub($call_number);
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

        ksort($reports);
        
        $real_reports = [];
       
        foreach ($reports as $key => $report) {
            $data = [
                $key, $report['volumes'], $report['no_of_titles']
            ];
            array_push($real_reports, $data);
        }
        array_unshift($real_reports, ['Date Of Publication', 'volumes', 'No. of titles']);

        return $real_reports;
    }

    public function createChart()
    {
        $data = $this->getReports();

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $this->insertLogo($worksheet, self::TITLE);

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
