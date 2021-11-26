<?php 

    include '../db_connect.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        if($_POST['password'] != $_POST['confirm_password']){
            header("location:../register.php?err=1");
            exit();
        }

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        
        $sql_register = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
        $res_register = mysqli_query($dbconn, $sql_register);

        if($res_register){
            // redirect to login with new credentials
            header("location:../index.php?success=1");
            exit();
        }else{
            header("location:../register.php?err=2");
            exit();
        }

    }else{
        header("location:../register.php?err=0");
        exit();
    }

?>