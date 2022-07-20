<?php
   
 require_once '../DbUserOperations.php';  
 $response=array(); 
 if(isset($_POST['user_id'])){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $db = new DbUserOperations();
        $list = $db->getAllLotteryContests($_POST['user_id']);
        if($list == 0){
            $response['error'] = true;
            $response['message'] = 'There are no Contests';
        }else{
            $response['error'] = false;
            $response['list'] = $list;
            $response['message'] = 'Contests get Successfully';
        }
      

    }else{
        $response['error'] = true;
        $response['message'] = 'Invalid Request';
    }

}else{
    $response['error'] = true;
    $response['message'] = 'Required fields are missing';
}
 
echo json_encode($response);

?>