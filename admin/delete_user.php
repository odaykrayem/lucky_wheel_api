<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['user_id'])){
        $db = new DbAdminOperations();
        $result = $db->deleteUser($_POST['user_id'],);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = "User deleted Successfully";
        }elseif($result == 2){
            $response['error'] = true;
            $response['message'] = "User was not deleted please try again";
        }else{
            $response['error'] = true;
            $response['message'] = "User is not exist";
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