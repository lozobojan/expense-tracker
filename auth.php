<?php 

    session_start();
    if($_SESSION['login'] != true){
        header('location:index.php');
        exit();
    }
    
    $currentUserId = $_SESSION['user']['id'];

?>