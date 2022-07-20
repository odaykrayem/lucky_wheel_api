<?php
   
 require_once '../DbAdminOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['']) and isset($_POST[''])){
        $db = new DbAdminOperations();

        
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