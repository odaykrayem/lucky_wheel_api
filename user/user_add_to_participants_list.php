<?php
   
 require_once '../DbUserOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    //TODO: change input params
    if(isset($_POST['user_id']) and isset($_POST['contest_id'])){
        $db = new DbUserOperations();
        $result = $db->addUserToParticipantsList($_POST['user_id'],$_POST['contest_id']);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = "User added to ParticipantsList list Successfully";
        }elseif($result == 2){
            $response['error'] = true;
            $response['message'] = "User was not added to ParticipantsList list please try again";
        }elseif($result == 3){
            $response['error'] = true;
            $response['message'] = "User was not added because already exist";
        }else{
            $response['error'] = true;
            $response['message'] = "User was not added it is not exist in Users";
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