<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['participant_id'])){
        $db = new DbAdminOperations();
        $result = $db->deleteParticipant($_POST['participant_id'],);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = "Participant deleted Successfully";
        }elseif($result == 2){
            $response['error'] = true;
            $response['message'] = "Participant was not deleted please try again";
        }else{
            $response['error'] = true;
            $response['message'] = "Participant is not exist";
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