<?php 

require '../vendor/autoload.php';
include '../db_connect.php';
include '../auth.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $is_grouped = $_SESSION['report_data_grouped'];

    // prepare headlines
    $sheet->setCellValue('A1', 'Tip troška');
    if(!$is_grouped){
        $sheet->setCellValue('B1', 'Datum');
        $sheet->setCellValue('C1', 'Iznos');
    }else{
        $sheet->setCellValue('B1', 'Iznos');
    }

    // read report data from "cache"
    $report_data = $_SESSION['report_data'];
    $currRowIndex = 3;

    foreach($report_data as $row){
        $sheet->setCellValue('A'.$currRowIndex, $row['type_name']);
        if(!$is_grouped){
            $sheet->setCellValue('B'.$currRowIndex, date('d.m.Y', strtotime($row['date'])));
            $sheet->setCellValue('C'.$currRowIndex, number_format($row['amount'], 2)." €");
        }else{
            $sheet->setCellValue('B'.$currRowIndex, number_format($row['amount'], 2)." €");
        }
        
        $currRowIndex++;
    }


    $fileName = "report_".uniqid().'.xlsx';
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');

?>