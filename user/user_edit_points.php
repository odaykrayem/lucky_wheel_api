<?php
require_once '../DbUserOperations.php';  
$response=array(); 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['user_id']) and isset($_POST['edit_points_operation']) and isset($_POST['points'])){
       $edit_points_opertaion = $_POST['edit_points_operation'];
       $db = new DbUserOperations();
        $result = $db->editUserPoints($_POST['user_id'], $_POST['edit_points_operation'], $_POST['points']);
        if($result == 3 && isset($_POST['edit_points_operation']) == 'incraese'){
            $response['error'] = false;
            $response['message'] = 'Points added successfully';
        }elseif($result == 2){
            $response['error'] = false;
            $response['message'] = 'Points decreased successfully';
        }elseif($result == -1){
            $response['error'] = true;
            $response['message'] = 'Not enough points';
        }else{
            $response['error'] = true;
            $response['message'] = 'User is not existed';
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