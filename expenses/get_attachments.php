<?php 

    include '../db_connect.php';
    include '../auth.php';

    if(isset($_GET['expense_id'])){
        $expense_id = $_GET['expense_id'];
        $sql = "SELECT * FROM attachments WHERE expense_id = $expense_id ORDER BY id DESC";
        $res = mysqli_query($dbconn, $sql);

        $attachments = [];
        while($attachment = mysqli_fetch_assoc($res)){
            $attachments[] = $attachment;
        }

        echo json_encode($attachments);
    }

?>