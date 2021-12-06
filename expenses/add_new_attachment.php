<?php 

    include '../functions.php';
    include '../db_connect.php';
    include '../auth.php';

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', "application/pdf"];

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        $expense_id = $_POST['expense_id'];
        $description = $_POST['description'];
        $newFilePath = "null";

        if(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0 ){
            $newFilePath = uploadFile($_FILES['fileToUpload'], $allowed_types, 1);
            if(!$newFilePath){
                header("location:../dashboard.php?attachment_saved=0&msg=wrong_file_format");
                exit();
            }
        }

        $sql_insert = "INSERT INTO attachments (`description`, file_path, expense_id) VALUES ('$description', $newFilePath, $expense_id)";
        $res_insert = mysqli_query($dbconn, $sql_insert);

        if($res_insert){
            header("location:../dashboard.php?attachment_saved=1");
            exit();
        }else{
            header("location:../dashboard.php?attachment_saved=0");
            exit();
        }

    }
?>