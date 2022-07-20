<?php
   
 require_once '../DbUserOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    $db = new DbUserOperations();
    $list = $db->getAllWinners();
    if($list == 0){
        $response['error'] = true;
        $response['message'] = 'There are no winners yet';
    }else{
    $response['error'] = false;
    $response['list'] = $list;
    $response['message'] = 'Get winners Successfully';
    }
}else{
    $response['error'] = true;
    $response['message'] = 'Invalid Request';
}

echo json_encode($response);

?>