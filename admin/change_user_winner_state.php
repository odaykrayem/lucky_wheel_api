<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['user_id']) and isset($_POST['state']) and isset($_POST['contest_id'])){
        $db = new DbAdminOperations();
        $result = $db->editUserWinnerState($_POST['user_id'], $_POST['state'],$_POST['contest_id']);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = 'User chnaged to winner successfully';
        }elseif($result == 2){
            $response['error'] = false;
            $response['message'] = 'User chnaged to not winner successfully';
        }elseif($result == 3){
            $response['error'] = true;
            $response['message'] = 'User Already winner';
        }elseif($result == 4){
            $response['error'] = true;
            $response['message'] = 'User Already not winner';
        }elseif($result == 5){
            $response['error'] = true;
            $response['message'] = 'User not participant';
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