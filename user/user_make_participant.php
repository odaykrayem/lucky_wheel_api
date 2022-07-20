<?php

require_once '../DbUserOperations.php';  
$response=array(); 
if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['user_id']) and isset($_POST['contest_id'])){

        $db = new DbUserOperations();
            if($db->isUserAlreadParticipantInTheContest($_POST['user_id'],$_POST['contest_id'])){
                $response['error'] = true;
                $response['message'] = 'أنت مشترك في هذه المسابقة';
            }else{
                $result=$db->addUserToParticipantsList($_POST['user_id'],$_POST['contest_id']);
                if($result == 1){
                    $response['error'] = false;
                    $response['message'] = "You have participated the contest Successfully";
                }elseif($result == 0){
                    $response['error'] = true;
                    $response['message'] = "Operation failed you are not a valid user";
                }elseif($result == 2){
                    $response['error'] = true;
                    $response['message'] = "Operation failed you can try again later";
                }
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