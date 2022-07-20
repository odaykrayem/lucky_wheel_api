<?php
require_once '../DbUserOperations.php';  
$response=array(); 
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['user_id']) and isset($_POST['points'])){
       $db = new DbUserOperations();
       
       $edit_points_result = $db->editUserPoints($_POST['user_id'], 'decrease', $_POST['points']);

       //2: success
       //0 : points less than user points
       //1: points less than minimum price
       if( $edit_points_result == 2){
           
            $pointsTodecrease = floatval($_POST['points']);
            $priceFromDB = floatval($db->getPointsPrice());
            $pointsFromDB = floatval($db->getPointsFromPrice());

            $finalBalance = $pointsTodecrease * $priceFromDB / $pointsFromDB;
            $increse_balance_result = $db->editUserBalance($_POST['user_id'], 'increase', $finalBalance);
            if($increse_balance_result == 3){
                $response['error'] = false;
                $response['message'] = 'تم نحويل النقاط إلى رصيد بنجاح';
            }else{
                $response['error'] = true;
                $response['message'] = 'المستخدم غير موجود';
            }
        }elseif($edit_points_result == -1){
            $response['error'] = true;
            $response['message'] = 'النقاط لديك أقل من النقاط المحولة';
        }elseif($edit_points_result == 1){
            $response['error'] = true;
            $response['message'] = 'النقاط لديك لا تكفي للتحويل';
        }elseif($edit_points_result == 4){
            $response['error'] = true;
            $response['message'] = 'يجب استخدام مقدار نقاط يقبل القسمة على 100';
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