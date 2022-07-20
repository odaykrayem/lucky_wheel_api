<?php

require_once '../DbUserOperations.php';  
$response=array(); 
if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['email']) and isset($_POST['password'])){

        $db = new DbUserOperations();
        if($db->isUserEmailAlreadyExist($_POST['email'])){
            if($db->loginUser($_POST['email'], $_POST['password'])){
                $user = $db->getUserByEmail($_POST['email']);
                $response['error'] = false;
                $response['message'] = 'Log in Successfully';
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
            $response['message'] = 'user email does not exist';
    
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