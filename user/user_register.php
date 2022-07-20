<?php
   
 require_once '../DbUserOperations.php';  
 $response=array(); 
 if($_SERVER['REQUEST_METHOD']=='POST'){
    if(
       isset($_POST['first_name']) and
         isset($_POST['middle_name']) and
          isset($_POST['last_name']) and
           isset($_POST['email']) and
            isset($_POST['password']) 
    ){
       $db = new DbUserOperations();
       $result = $db-> createUser(
         $_POST['first_name'],
         $_POST['middle_name'],
         $_POST['last_name'],
         $_POST['email'],
         $_POST['password'],
       );
       if($result == 1){
            $response['error'] = false;
            $response['message'] = "User registered Successfully";
            if(isset($_POST['code_from_user'])){
               //add points to refferal code other user
               $code_from_another_user_result =  $db->addPointstoUserOnReferral($_POST['code_from_user']); 
              if($code_from_another_user_result == 1){
                 $response['ref_code_msg'] = "Reffferal code exist";        
              }else{
                 $response['ref_code_msg'] = "Reffferal code not exist";        
              }

            }else{
               $response['ref_code_msg'] = "Reffferal code not exist";        
            }
         }elseif($result == 2){
            $response['error'] = true;
            $response['ref_code_msg'] = "Reffferal code not exist";        
            $response['message'] = "User Not Registered please try again later";
         }elseif($result == 0){
            $response['error'] = true;
            $response['ref_code_msg'] = "Reffferal code not exist";        
            $response['message'] = "Email already Exist please choose different email";
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