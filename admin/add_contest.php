<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['prize']) and isset($_POST['draw_date']) and isset($_POST['name'] )and isset($_POST['draw_time'])){
        $db = new DbAdminOperations();
        $result=$db->addLotteryContest($_POST['prize'],$_POST['draw_date'],$_POST['name'],$_POST['draw_time']);
        if($result == 1){
            $response['error'] = false;
            $response['message'] = "Contest added Successfully";
        }elseif($result == 0){
            $response['error'] = true;
            $response['message'] = "Contest was not added please try again";
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