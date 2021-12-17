<?php 
    
    include '../db_connect.php';
    include '../auth.php';
    
    $sql_report = " SELECT 
                         e.date,
                         e.amount,
                         et.name as type_name
                    FROM expenses e 
                    JOIN expense_types et on e.expense_type_id = et.id
                    WHERE true AND user_id = $currentUserId";

    if(isset($_POST['date_from']) && !empty($_POST['date_from'])) 
        $sql_report .= " and date >= "."'".date('Y-m-d', strtotime($_POST['date_from']))."'";
    if(isset($_POST['date_to']) && !empty($_POST['date_to'])) 
        $sql_report .= " and date <= "."'".date('Y-m-d', strtotime($_POST['date_to']))."'";
    if(isset($_POST['amount_from']) && is_numeric($_POST['amount_from']) ) 
        $sql_report .= " and amount >= ".$_POST['amount_from'];
    if(isset($_POST['amount_to']) && is_numeric($_POST['amount_to'])) 
        $sql_report .= " and amount <= ".$_POST['amount_to'];
    if(isset($_POST['expense_type_id']) && !empty($_POST['expense_type_id'])) 
        $sql_report .= " and expense_type_id = ".$_POST['expense_type_id'];

    $res_report = mysqli_query($dbconn, $sql_report);
    $res_arr = [];

    while($row_report = mysqli_fetch_assoc($res_report)){
        $res_arr[] = [
            "date" => date('d.m.Y', strtotime($row_report['date'])),
            "amount" => $row_report['amount'],
            "type_name" => $row_report['type_name'],
        ];
    }

    echo json_encode($res_arr);

?>