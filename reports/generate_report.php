<?php 
    
    include '../db_connect.php';
    include '../auth.php';
    include './functions.php';
    
    $base_query = getBaseQuery($currentUserId);
    $sql_report = readCriteriaAndConcatenateClauses($_POST, $base_query);

    $res_report = mysqli_query($dbconn, $sql_report);
    $res_arr = [];

    while($row_report = mysqli_fetch_assoc($res_report)){
        $res_arr[] = [
            "date" => date('d.m.Y', strtotime($row_report['date'])),
            "amount" => $row_report['amount'],
            "type_name" => $row_report['type_name'],
        ];
    }

    $_SESSION['report_data'] = $res_arr; // simuliramo cache
    $_SESSION['report_data_grouped'] = false;

    echo json_encode($res_arr);

?>