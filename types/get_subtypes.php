<?php 

    include '../db_connect.php';

    if(isset($_GET['type_id'])){

        $type_id = $_GET['type_id'];

        $sql = "SELECT * FROM expense_subtypes WHERE expense_type_id = $type_id ";
        $res = mysqli_query($dbconn, $sql);

        $subtypes = [];
        while($subtype = mysqli_fetch_assoc($res)){
            $subtypes[] = $subtype;
        }

        echo json_encode($subtypes);

    }

?>