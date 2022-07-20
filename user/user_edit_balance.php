<?php
require_once '../DbUserOperations.php';  
$response=array(); 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['user_id']) and isset($_POST['edit_balance_operation']) and isset($_POST['money'])){
       $edit_balance_operation = $_POST['edit_balance_operation'];
       $db = new DbUserOperations();
        $result = $db->editUserBalance($_POST['user_id'], $_POST['edit_balance_operation'], $_POST['money']);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = 'extra money added successfully';
        }elseif($result == 2){
            $response['error'] = false;
            $response['message'] = 'balance decreased successfully';
        }elseif($result == 3){
            $response['error'] = true;
            $response['message'] = 'Not enough balance';
        }else{
            $response['error'] = true;
            $response['message'] = 'User is not existed';
        }
       
    }else{
        $response['error'] = true;
        $response['message'] = 'Required fields are missing';
    }
}else{
    $response['error'] = true;
    $response['message'] = 'Invalid Request';
}

echo json_encode($response);

?>