<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['contest_id'])){
        $db = new DbAdminOperations();
        $result = $db->deleteLotteryContest($_POST['contest_id'],);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = "Contest deleted Successfully";
        }elseif($result == 2){
            $response['error'] = true;
            $response['message'] = "Contest was not deleted please try again";
        }else{
            $response['error'] = true;
            $response['message'] = "Contest is not exist";
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