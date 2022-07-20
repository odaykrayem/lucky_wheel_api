<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){
        $db = new DbAdminOperations();
        $list = $db->getAllWithdrawalRequests();
        if($list == 0){
            $response['error'] = true;
            $response['message'] = 'لا يوجد طلبات';
        }else{
            $response['error'] = false;
            $response['list'] = $list;
            $response['message'] = 'تم تحميل الطلبات بنجاح';
        }
      

}else{
    $response['error'] = true;
    $response['message'] = 'Invalid Request';
}

echo json_encode($response);

?>