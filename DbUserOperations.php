<?php

 class DbUserOperations{
     private $con;
     function __construct()
     {
        require_once dirname(__FILE__).'/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();    
     }

     /**
     * User Register functions
     */
      public function createUser($first_name, $middle_name, $last_name, $email, $pw)
      {
        /**
         * generate referral code
         */
        if($this->isUserEmailAlreadyExist($email)){
            return 0;
        }else{
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
            do{
                $res = "";
                for ($i = 0; $i < 20; $i++) {
                    $res .= $chars[mt_rand(0, strlen($chars)-1)];
                }
            }while($this->isReferralCodeExist($res));

            $referral_code = $res;
            $password = md5($pw);
            $stmt = $this-> con->prepare("INSERT INTO `users` (`first_name`, `middle_name`, `last_name`, `email`, `password`, `referral_code`) VALUES (?, ?, ?, ?, ?, ?);");
            $stmt->bind_param("ssssss",$first_name,$middle_name,$last_name,$email,$password,$referral_code);
            if($stmt->execute()){
                return 1;
            }else{
                return 2;
            }
        }   
    }

    public function isReferralCodeExist($ref_code) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM users WHERE referral_code = ?;");
        $stmt->bind_param("s",$ref_code);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    
    public function checkReferralTimes($ref_code){
        $stmt = $this-> con->prepare("SELECT * FROM users WHERE referral_code = ? and ref_times > 0 ;");
        $stmt->bind_param("s",$ref_code);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0; 
    }
    public function decreaseReferralTimes($ref_code){
        $stmt = $this-> con->prepare("update users set ref_times = ref_times-1 WHERE referral_code = ?;");
        $stmt->bind_param("s",$ref_code);
        $stmt->execute();
    }
    public function addPointstoUserOnReferral($other_user_ref_code) 
    {
        if($this->isReferralCodeExist($other_user_ref_code)){
            if($this->checkReferralTimes($other_user_ref_code)){
                $stmt = $this-> con->prepare("Update users set points = points + 50 where referral_code = ?;");
                $stmt->bind_param("s",$other_user_ref_code);
                $stmt->execute();
                $this->decreaseReferralTimes($other_user_ref_code);
                return 1;
            }else{
                return 0;
            }
           
        }else{
            return 0;
        }
    }
    /**
     * User Log in functions
     */
 
   
    public function loginUser($email, $pass){
        $password = md5($pass);
        $stmt = $this-> con->prepare("SELECT id FROM users WHERE email = ? AND password = ?;");
        $stmt->bind_param("ss",$email,$password);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
   /**
  * Edit user Money exist
  */
  public function editUserBalance($id, $edit_balance_opertaion, $money){
    if($this->isUserIdExistInUsers($id)){
        if($edit_balance_opertaion == 'increase'){
            $stmt = $this-> con->prepare("Update users set balance = balance + ? where id = ?;");
            $stmt->bind_param("ss",$money,$id);
            $stmt->execute();
            return 3;
        }elseif($edit_balance_opertaion == 'decrease'){
            $can_decrese = $this->isMoneyEnough($id, $money);
            if($can_decrese == 2){
               $stmt = $this-> con->prepare("Update users set balance = balance - ? where id = ?;");
               $stmt->bind_param("ss",$money,$id);
               $stmt->execute();
               return 2;
            }else if($can_decrese == -1){
                return -1;
            }else if($can_decrese == 1){
              return 1;
            }
        }
    }else{
        return 0;
    }
}

public function addToWithdrawalRequest($user_id,$amount, $bank_code, $date)
{
    $stmt = $this-> con->prepare("INSERT INTO `withdrawal_requests` (`user_id`, `amount`, `bank_code`, `date`) VALUES (?, ?, ?, ?);");
    $stmt->bind_param("ssss",$user_id,$amount,$bank_code,$date);
    if($stmt->execute()){
        return 1;
    }else{
        return 2;
    }
 
}

public function isMoneyEnough($user_id, $money_to_decrease) 
{
    $stmt = $this-> con->prepare("SELECT balance FROM users WHERE id = ?;");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $user_balance = $stmt->get_result()->fetch_assoc(); 
        
        if($user_balance['balance'] < $money_to_decrease){
            return -1;
        }
       else{
            if($money_to_decrease < $this->getMinimumBalance()) {
                return 1;
             }else{
                 return 2;
             }
        }
}
public function getMinimumBalance(){
    $stmt = $this-> con->prepare("SELECT minimum_balance FROM `minimum_balance` WHERE id = 1");
    $stmt->execute();
    $balance = $stmt->get_result()->fetch_assoc();  
    return $balance['minimum_balance'];
}

/**
 * Edit User Points Functions
 */
/**
 * 1: increased successfully
 * 2: decreased successfully
 * 3: not enough points to decrease
 * 0: user is not exist
 */
    public function editUserPoints($id, $edit_points_opertaion, $points){
        if($this->isUserIdExistInUsers($id)){
            if($edit_points_opertaion == 'increase'){
                $stmt = $this-> con->prepare("Update users set points = points + ? where id = ?;");
                $stmt->bind_param("ss",$points,$id);
                $stmt->execute();
                return 3;
            }elseif($edit_points_opertaion == 'decrease'){
                 $can_decrese = $this->isPointsEnough($id, $points);
                 if($can_decrese == 2){
                    // if($_POST['points'] % 100 == 0){
                        $stmt = $this-> con->prepare("Update users set points = points - ? where id = ?;");
                        $stmt->bind_param("ss",$points,$id);
                        $stmt->execute();
                        return 2;
                    // }else{
                    //     return 4;
                    // }
                   
                 }else if($can_decrese == -1){
                     return -1;
                 }else if($can_decrese == 1){
                   return 1;
                 }
            }
        }else{
            return 0;
        }
    }

    // public function transferPointsToBalance($user_id, $points){
    //     if($this->isPointsEnough($user_id, $points)){
    //         $pointsPrice = $this->getPointsPrice();
    //         $decrease = $this->editUserPoints($user_id, "decrease",$points);

    //     }else{
    //         return 0;
    //     }

    // }
    public function isPointsEnough($user_id, $points_to_decrease) 
    {
        $stmt = $this-> con->prepare("SELECT points FROM users WHERE id = ?;");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $user_points = $stmt->get_result()->fetch_assoc(); 
        
        if($user_points['points'] < $points_to_decrease){
            return -1;
        }else{
            if($points_to_decrease < $this->getPointsFromPrice()) {
                return 1;
             }else{
                 return 2;
             }
        }
    }

    public function getPointsFromPrice(){
        $stmt = $this-> con->prepare("SELECT points FROM `points_price` WHERE id = 1");
        $stmt->execute();
        $points = $stmt->get_result()->fetch_assoc();  
        return $points['points'];
    }

    public function getPointsPrice(){
        $stmt = $this-> con->prepare("SELECT price FROM `points_price` WHERE id = 1");
        $stmt->execute();
        $price = $stmt->get_result()->fetch_assoc();  
        return $price['price'];
    }
    
    
 

    public function addUserToParticipantsList($user_id, $contest_id) 
    {
        if($this->isUserIdExistInUsers($user_id)){
                  $stmt = $this-> con->prepare("INSERT INTO `participants` (`user_id`, `contest_id`) VALUES (? , ?);");
                  $stmt->bind_param("ss",$user_id, $contest_id);
                  if($stmt->execute()){
                      return 1;
                  }else{
                      return 2;
                  }
              
        }else{
            return 0;
        }
      
    }

    /**
     * Utility functions
     */
    public function isUserIdExistInUsers($user_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM users WHERE id = ?;");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    
    public function getUserByEmail($email){
        $stmt = $this-> con->prepare("SELECT * FROM users WHERE email = ?;");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function isUserEmailAlreadyExist($user_email) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM users WHERE email = ?;");
        $stmt->bind_param("s",$user_email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;  
    }
    public function getUserById($id){
        $stmt = $this-> con->prepare("SELECT * FROM users WHERE id = ?;");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function getAllLotteryContests($user_id){
        $stmt = $this->con->prepare("SELECT id, prize, draw_date, draw_time FROM lottery_contests;");
        $stmt ->execute();
        $stmt ->bind_result($id,$prize, $draw_date, $draw_time);
        $stmt->store_result();
        $contests_list = array();
        if($stmt->num_rows>0){
            while($stmt ->fetch()){

                $temp = array();
                
                $temp['id'] = $id;
                $temp['prize'] = $prize;
                $temp['draw_date'] = $draw_date;
                $temp['draw_time'] = $draw_time;
                if($this->isUserAlreadParticipantInTheContest($user_id,$id)){
                    $temp['is_participant'] = true;
                }else{
                    $temp['is_participant'] = false;
                }

                array_push($contests_list,$temp);
    
           }
           return $contests_list;
        }
        else{
            return 0;
        }

    }
    public function isUserAlreadyInParticipantList($user_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM participants WHERE user_id = ?;");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    public function isUserAlreadParticipantInTheContest($user_id,$contest_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM `participants` WHERE user_id = ? and contest_id = ?;");
        $stmt->bind_param("ss",$user_id,$contest_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
   
   
    public function getAllWinners(){
        $stmt = $this->con->prepare("SELECT CONCAT_WS('', users.first_name,' ',users.middle_name,' ', users.last_name) as user_name ,users.id,users.email, lottery_contests.prize, lottery_contests.draw_date FROM users,lottery_contests,participants WHERE users.id = participants.user_id and lottery_contests.id = participants.contest_id and is_winner = 1;");
        $stmt ->execute();
        $stmt ->bind_result($user_name,$user_id,$email, $prize, $draw_date);
        $stmt->store_result();
        $list = array();
        if($stmt->num_rows>0){
            while($stmt ->fetch()){

                $temp = array();
                
                $temp['user_name'] = $user_name;
                $temp['user_id'] = $user_id;
                $temp['email'] = $email;
                $temp['prize'] = $prize;
                $temp['draw_date'] = $draw_date;
                array_push($list,$temp);
    
           }
           return $list;
        }else{
            return 0;
        }
       

    }
    
 }
?>