<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){
        $db = new DbAdminOperations();
        $list = $db->getAllUsers();
        if($list == 0){
            $response['error'] = true;
            $response['message'] = 'There are no users';
        }else{
            $response['error'] = false;
            $response['list'] = $list;
            $response['message'] = 'GET ALL USERS SUCCESSFULLY';
        }
       

}else{
    $response['error'] = true;
    $response['message'] = 'Invalid Request';
}

echo json_encode($response);

?>