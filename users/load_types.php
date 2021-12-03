<?php 
    
    include '../db_connect.php';
    include '../auth.php';
    
    $sql_types = "SELECT 
                    et.id,
                    et.name,
                    uet.user_id 
                from expense_types et 
                join user_expense_type uet on et.id = uet.expense_type_id and uet.user_id = $currentUserId
                order by et.name ASC
    ";
    $res_types = mysqli_query($dbconn, $sql_types);

    $types = [];

    while($type = mysqli_fetch_assoc($res_types)){
        $types[] = $type;
    }

    echo json_encode($types);

?>