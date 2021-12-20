<?php 

require '../vendor/autoload.php';
include '../db_connect.php';
include '../auth.php';
include './functions.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // prepare headlines
    $sheet->setCellValue('A1', 'Tip troška');
    $sheet->setCellValue('B1', 'Datum');
    $sheet->setCellValue('C1', 'Iznos');

    // get data from DB
    /* if($_GET['grouped'] == 1){
        $base_query = getBaseQuery($currentUserId, true);
        $sql_report = readCriteriaAndConcatenateClauses($_POST, $base_query) . " group by et.name order by total desc ";  
    }
    else{
        $base_query = getBaseQuery($currentUserId);
        $sql_report = readCriteriaAndConcatenateClauses($_POST, $base_query);
    } */

    $sql_report = getBaseQuery($currentUserId);
    $res_report = mysqli_query($dbconn, $sql_report);
    
    $currRowIndex = 3;
    while($row = mysqli_fetch_assoc($res_report)){
        $sheet->setCellValue('A'.$currRowIndex, $row['type_name']);
        $sheet->setCellValue('B'.$currRowIndex, date('d.m.Y', strtotime($row['date'])));
        $sheet->setCellValue('C'.$currRowIndex, number_format($row['amount'], 2)." €");
        $currRowIndex++;
    }


    $fileName = "report_".uniqid().'.xlsx';
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');

?>