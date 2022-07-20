<?php
require_once '../DbUserOperations.php';  
$response=array(); 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['user_id']) and isset($_POST['balance']) and isset($_POST['bank_code'] ) and isset($_POST['date'])){
       $db = new DbUserOperations();
       $edit_balance_result = $db->editUserBalance($_POST['user_id'], 'decrease', $_POST['balance']);

       //2: success
       //0 : points less than user points
       //1: points less than minimum price
       if( $edit_balance_result == 2){
           
            $balanceTodecrease = $_POST['balance'];
         
            $add_to_list_result = $db->addToWithdrawalRequest($_POST['user_id'],$_POST['balance'], $_POST['bank_code'],$_POST['date']);
            if($add_to_list_result == 1){
                $response['error'] = false;
                $response['message'] = 'تم تأكيد الطلب بنجاح';
            }else{
                $response['error'] = true;
                $response['message'] = 'خطأ في العملية';
            }
        }elseif($edit_balance_result == -1){
            $response['error'] = true;
            $response['message'] = 'الرصيد لديك أقل من الرصيد المطلوب';
        }elseif($edit_balance_result == 1){
            $response['error'] = true;
            $response['message'] = 'الرصيد لديك لا يكفي للسحب';
        }elseif($edit_balance_result == 0){
            $response['error'] = true;
            $response['message'] = 'المستخدم غير موجود';
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