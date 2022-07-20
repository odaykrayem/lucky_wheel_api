<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){
        $db = new DbAdminOperations();
        $list = $db->getAllParticipants();
        if($list == 0){
            $response['error'] = true;
            $response['message'] = 'لا يوجد متسابقين';
        }else{
        $response['error'] = false;
        $response['list'] = $list;
        $response['message'] = 'تم تحميل المتسابقين بنجاح';
        }
}else{
    $response['error'] = true;
    $response['message'] = 'Invalid Request';
}

echo json_encode($response);

?>