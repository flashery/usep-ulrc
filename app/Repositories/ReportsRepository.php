<?php

namespace App\Repositories;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class ReportsRepository
{

    public function __construct()
    { }

    protected function generateRanges()
    {
        $data = [];
        $Dewey_decimal_start = 0;
        $Dewey_decimal_end = 100;
        $start = 0;
        $step = 100;
        $end = 1000;

        foreach (range($start, $end, $step) as $number) {

            if ($number >= $end) break;

            $Dewey_decimal_start =  str_pad($number, 3, '0', STR_PAD_LEFT); // add 0s to the left of the digits if digits lesser than 3
            $Dewey_decimal_end = str_pad($number + $step  - 1, 3, '0', STR_PAD_LEFT); // add 0s to the left of the digits if digits lesser than 3

            array_push($data, ['start' => $Dewey_decimal_start, 'end' => $Dewey_decimal_end]);
        }

        return $data;
    }

    protected function insertLogo($worksheet)
    {
        $drawing = new Drawing();
        $drawing->setName('Paid');
        $drawing->setDescription('Paid');
        $drawing->setPath(public_path() . '/images/usep-logo.png'); // put your path and image here
        $drawing->setCoordinates('A1');
        // $drawing->setWidth(190);
        $drawing->setHeight(80);
        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($worksheet);

        // Add main title
        $main_title = 'UNIVERSITY OF SOUTHEASTERN PHILIPPINES';

        $main_title_style = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 15,
                'name'  => 'Verdana'
            )
        );

        $worksheet->setCellValue('B1', $main_title);
        $worksheet->mergeCells('B1:G1');
        $worksheet->getStyle('B1:G1')->applyFromArray($main_title_style);

        // Add sub title
        $sub_title = 'University Learning Resource Center';
        $sub_title_style = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 13,
                'name'  => 'Verdana'
            )
        );
        $worksheet->setCellValue('B2', $sub_title);
        $worksheet->mergeCells('B2:G2');
        $worksheet->getStyle('B2:G2')->applyFromArray($sub_title_style);

        // Add description
        $description = 'Graphical Representation of All Collections';
        $description_style = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '000000'),
                'size'  => 10,
                'name'  => 'Verdana'
            )
        );
        $worksheet->setCellValue('B5', $description);
        $worksheet->mergeCells('B5:G5');
        $worksheet->getStyle('B5:G5')->applyFromArray($description_style);
    }
}
