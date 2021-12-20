<?php 

    function readCriteriaAndConcatenateClauses($arr, $query){
        
        if(isset($arr['date_from']) && !empty($arr['date_from'])) 
            $query .= " and date >= "."'".date('Y-m-d', strtotime($arr['date_from']))."'";
        if(isset($arr['date_to']) && !empty($arr['date_to'])) 
            $query .= " and date <= "."'".date('Y-m-d', strtotime($arr['date_to']))."'";
        if(isset($arr['amount_from']) && is_numeric($arr['amount_from']) ) 
            $query .= " and amount >= ".$arr['amount_from'];
        if(isset($arr['amount_to']) && is_numeric($arr['amount_to'])) 
            $query .= " and amount <= ".$arr['amount_to'];
        if(isset($arr['expense_type_id']) && !empty($arr['expense_type_id'])) 
            $query .= " and expense_type_id = ".$arr['expense_type_id'];

        return $query;
    }

    function getBaseQuery($currentUserId, $isGrouped = false){
        $columns = "
                    e.date,
                    e.amount,
        ";
        if($isGrouped){
            $columns = " sum(e.amount) as total,";
        }
        return " SELECT 
                        $columns
                        et.name as type_name
                FROM expenses e 
                JOIN expense_types et on e.expense_type_id = et.id
                WHERE user_id = $currentUserId ";
    }
?>