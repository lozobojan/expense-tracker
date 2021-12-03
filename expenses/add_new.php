<?php 

    include '../db_connect.php';
    include '../auth.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $expense_type_id = $_POST['expense_type_id'];
        $expense_subtype_id = isset($_POST['expense_subtype_id']) ? $_POST['expense_subtype_id'] : "null";

        // format the date  
        $date = date("Y-m-d", strtotime($date) );

        $sql_insert = "INSERT INTO expenses (amount, date, expense_type_id, expense_subtype_id, user_id)
                        VALUES ($amount, '$date', $expense_type_id, $expense_subtype_id, $currentUserId)
        ";

        $res_insert = mysqli_query($dbconn, $sql_insert);

        if($res_insert){
            header("location:../dashboard.php?success=1");
            exit();
        }else{
            header("location:../dashboard.php?success=0");
            exit();
        }

    }

?>