<?php
   
//  require_once '../DbOperations.php';  
 $response=array(); 

 require_once dirname(__FILE__).'/DbConnect.php';
 $db = new DbConnect();
 $con = $db->connect();  
 $stmt = $con->prepare("SELECT prize FROM `lottery_contests` WHERE id = 31");
//  $stmt->bind_param("s",$contest_id);
 $stmt->execute();
 $contest = $stmt->get_result()->fetch_assoc();  
 echo $contest['prize'];
 $id = '57';
 $edit_balance_opertaion = 'increase';
 $money = floatval($contest['prize']);
    if($edit_balance_opertaion == 'increase'){
        $con1 = $db->connect(); 
        $stmt = $con1->prepare("Update `users` set balance = balance + ? where id = ?;");
        $stmt->bind_param("ss",$money,$id);
        $stmt->execute();
        echo 3;
    }elseif($edit_balance_opertaion == 'decrease'){
        // $can_decrese = isMoneyEnough($id, $money);
        if($can_decrese == 2){
           $stmt = $this-> con->prepare("Update `users``users` set balance = balance - ? where id = ?;");
           $stmt->bind_param("ss",$money,$id);
           $stmt->execute();
           echo 2;
        }else if($can_decrese == -1){
            echo -1;
        }else if($can_decrese == 1){
            echo 1;
        }
    }

        
