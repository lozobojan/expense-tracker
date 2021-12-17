<?php 

    include '../db_connect.php';
    include '../auth.php';
    include '../functions.php';

    $sql = "SELECT  et.name,
                    et.color, 
                    sum(e.amount) as total
                from expenses e 
                join expense_types et on e.expense_type_id = et.id
            where e.user_id = $currentUserId
            group by et.name order by total desc;";
    
    $res = mysqli_query($dbconn, $sql);

    $labels = [];
    $data = [];
    $colors = [];
    $total = 0;

    while($row = mysqli_fetch_assoc($res)){
        $labels[] = $row['name'];
        $data[] = $row['total'];
        $colors[] = $row['color'] ?? generateColor() ;
        $total += $row['total'];
    }

    $res_arr = [
        "labels" => $labels,
        "data" => $data,
        "colors" => $colors,
        "total" => number_format($total,2),
    ];

    echo json_encode($res_arr);

?>