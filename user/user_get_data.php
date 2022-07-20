<?php

require_once '../DbUserOperations.php';  
$response=array(); 
if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['id'])){

        $db = new DbUserOperations();
        if($db->isUserIdExistInUsers($_POST['id'])){
            $user = $db->getUserById($_POST['id']);
            $response['error'] = false;
            $response['message'] = 'User data got Successfully';
            $response['id']  = $user['id'];
            $response['first_name']  = $user['first_name'];
            $response['middle_name']  = $user['middle_name'];
            $response['last_name']  = $user['last_name'];
            $response['email']  = $user['email'];
            $response['points']  = $user['points'];
            $response['balance']  = $user['balance'];
            $response['referral_code']  = $user['referral_code'];
            $response['ref_times']  = $user['ref_times'];


        }else{
            $response['error'] = true;
            $response['message'] = 'Wrong email or password';
    
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