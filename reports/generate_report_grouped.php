<?php 
    
    include '../db_connect.php';
    include '../auth.php';
    include './functions.php';
    
    $base_query = getBaseQuery($currentUserId, true);
    $sql_report = readCriteriaAndConcatenateClauses($_POST, $base_query) . " group by et.name order by total desc ";

    $res_report = mysqli_query($dbconn, $sql_report);
    $res_arr = [];

    while($row_report = mysqli_fetch_assoc($res_report)){
        $res_arr[] = [
            "amount" => $row_report['total'],
            "type_name" => $row_report['type_name'],
        ];
    }

    echo json_encode($res_arr);

?>