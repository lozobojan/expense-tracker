<?php 

    include '../db_connect.php';
    include '../auth.php';

    if(isset($_POST['type_id'])){

        $type_id = $_POST['type_id'];
        
        $sql_exists = "SELECT count(*) as cnt from user_expense_type uet where user_id = $currentUserId and expense_type_id = $type_id";
        $res_exists = mysqli_query($dbconn, $sql_exists);
        $row_exists = mysqli_fetch_assoc($res_exists);

        $count = $row_exists['cnt'];

        if($count == 0){
            $sql = "INSERT INTO user_expense_type (user_id, expense_type_id) VALUES ($currentUserId, $type_id)";
        }else{
            $sql = "DELETE FROM user_expense_type WHERE user_id = $currentUserId AND expense_type_id = $type_id";
        }

        $res = mysqli_query($dbconn, $sql);
        if($res){
            echo json_encode(['status' => true]);
        }else{
            echo json_encode(['status' => false]);
        }

    }

?>