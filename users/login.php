<?php 

    include '../db_connect.php';
    session_start();

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $sql_login = "SELECT * FROM users WHERE email = '$email' AND password = '$password' ";
        $res_login = mysqli_query($dbconn, $sql_login);

        if(mysqli_num_rows($res_login) == 1){
            
            $user = mysqli_fetch_assoc($res_login);
            $_SESSION['login'] = true;
            $_SESSION['user'] = $user;

            header("location:../dashboard.php"); // successful login
            exit();    

        }else{
            header("location:../index.php?err=1"); // wrong credentials
            exit();
        }

    }else{
        header("location:../index.php?err=0");
        exit();
    }

?>