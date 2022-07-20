<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['request_id']) and isset($_POST['state'])){
        $db = new DbAdminOperations();
        $result = $db->editRequestState($_POST['request_id'], $_POST['state']);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = 'Request changed to done successfully';
        }elseif($result == 2){
            $response['error'] = false;
            $response['message'] = 'Request changed to not done successfully';
        }elseif($result == 3){
            $response['error'] = true;
            $response['message'] = 'Request Already done';
        }elseif($result == 4){
            $response['error'] = true;
            $response['message'] = 'Request Already not done';
        }else{
            $response['error'] = true;
            $response['message'] = 'Request is not existed';
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