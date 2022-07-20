<?php
class DbAdminOperations{
     private $con;
     function __construct()
     {
        require_once dirname(__FILE__).'/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();    
     }
    
       /**
     * Winners List Functions
     */
   
    public function isUserAlreadyWinner($user_id, $contest_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM participants WHERE user_id = ? and contest_id = ? and is_winner = 1;");
        $stmt->bind_param("ss",$user_id,$contest_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    public function isUserAlreadyNOTWinner($user_id,$contest_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM participants WHERE user_id = ? and contest_id = ? and is_winner = 0;");
        $stmt->bind_param("ss",$user_id,$contest_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }

    public function isUserIdExistInParticipants($user_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM participants WHERE user_id = ? ;");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
   
  /**
   * state: winner , not_winner 
     * 1: user state was changed to winner successfully
     * 2: user state was not changes changed to not winner successfully
     * 3: user state was not changes because alrady winner 
     * 4: user state was not changes because alrady not winner 
     * 5: user state was not changes because is not exist in participants table
     * 0: user state was not changes because is not exist in users table
     */
    public function editUserWinnerState($user_id, $edit_user_state , $contest_id){
        $prize = $this->getContestPrize($contest_id);

        if($this->isUserIdExistInUsers($user_id)){
          if($this->isUserIdExistInParticipants($user_id)){
            if($edit_user_state == 'winner'){
                if($this->isUserAlreadyWinner($user_id, $contest_id)){
                    return 3;
                }else{

                    $stmt = $this-> con->prepare("Update participants set is_winner = 1 where user_id = ? and contest_id = ?;");
                    $stmt->bind_param("ss",$user_id,$contest_id);
                    $stmt->execute();
                    $result = $this->editUserPoints($user_id,'increase',$prize);
                    if($result == 3){
                        return 1;
                    }else{
                        return -1;
                    }
                }  
            }elseif($edit_user_state == 'not_winner'){
                if($this->isUserAlreadyNOTWinner($user_id, $contest_id)){
                    return 4;
                }else{
                    $stmt = $this-> con->prepare("Update participants set is_winner = 0 where user_id = ? and contest_id = ?;");
                    $stmt->bind_param("ss",$user_id, $contest_id);
                    $stmt->execute();
                    $result = $this->editUserPoints($user_id,'decrease',$prize);

                    return 2;   
                }       
            }
          }else{
              return 5;
          }
        }else{
            return 0;
        }
    }

    public function getContestPrize($contest_id){
        $stmt = $this-> con->prepare("SELECT prize FROM `lottery_contests` WHERE id = ?");
        $stmt->bind_param("s",$contest_id);
        $stmt->execute();
        $contest = $stmt->get_result()->fetch_assoc();  
        return $contest['prize'];
    }
    public function removeUserFromparticipantsList($user_id) 
    {
        if($this->isUserIdExistInParticipants($user_id)){
            $stmt = $this-> con->prepare("DELETE FROM `participants` WHERE user_id = ?;");
            $stmt->bind_param("s",$user_id);
            if($stmt->execute()){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 0;
        }
       
    }
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

    public function editUserPoints($id, $edit_points_operation, $extraPoints){
        if($this->isUserIdExistInUsers($id)){
            if($edit_points_operation == 'increase'){
                $stmt = $this-> con->prepare("Update users set points = points + ? where id = ?;");
                $stmt->bind_param("ss",$extraPoints,$id);
                $stmt->execute();
                return 3;
            }elseif($edit_points_operation == 'decrease'){
                $can_decrese = $this->isPointsEnough($id, $extraPoints);
                if($can_decrese == 2){
                   $stmt = $this-> con->prepare("Update users set points = points - ? where id = ?;");
                   $stmt->bind_param("ss",$extraPoints,$id);
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
    public function isMoneyEnough($user_id, $points_to_decrease) 
    {
    $stmt = $this-> con->prepare("SELECT points FROM users WHERE id = ?;");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $user_points = $stmt->get_result()->fetch_assoc(); 
        
        if($user_points['points'] < $points_to_decrease){
            return -1;
        }
        else{
            return 2;
        }
    }

    public function isPointsEnough($user_id, $points_to_decrease) 
    {
    $stmt = $this-> con->prepare("SELECT balance FROM users WHERE id = ?;");
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $user_balance = $stmt->get_result()->fetch_assoc(); 
        
        if($user_balance['balance'] < $points_to_decrease){
            return -1;
        }
        else{
            return 2;
        }
    }
    public function getAllWinners(){
        $stmt = $this->con->prepare("SELECT participants.id, user_id, contest_id,  CONCAT_WS('', users.first_name,' ',users.middle_name,' ', users.last_name) as user_name ,users.email, lottery_contests.prize, lottery_contests.draw_date FROM users,lottery_contests,participants WHERE users.id = participants.user_id and lottery_contests.id = participants.contest_id and is_winner = 1;");
        $stmt ->execute();
        $stmt ->bind_result($id,$user_id,$contest_id,$user_name,$email, $prize, $draw_date);
        $stmt->store_result();
        $list = array();
        if($stmt->num_rows>0){
            while($stmt ->fetch()){

                $temp = array();
                
                $temp['id'] = $id;
                $temp['user_id'] = $user_id;
                $temp['contest_id'] = $contest_id;
                $temp['user_name'] = $user_name;
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
    public function getAllParticipants(){
        $stmt = $this->con->prepare("SELECT participants.id, user_id, contest_id,  CONCAT_WS('', users.first_name,' ',users.middle_name,' ', users.last_name) as user_name ,is_winner,users.email, lottery_contests.prize, lottery_contests.draw_date FROM users,lottery_contests,participants WHERE users.id = participants.user_id and lottery_contests.id = participants.contest_id;");
        $stmt ->execute();
        $stmt ->bind_result($id,$user_id,$contest_id,$user_name,$is_winner,$email, $prize, $draw_date);
        $stmt->store_result();
        $list = array();
        if($stmt->num_rows>0){

         while($stmt ->fetch()){

            $temp = array();
            
            $temp['id'] = $id;
            $temp['user_id'] = $user_id;
            $temp['contest_id'] = $contest_id;
            $temp['user_name'] = $user_name;
            $temp['is_winner'] = $is_winner;
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
    public function getAllLotteryContests(){
        $stmt = $this->con->prepare("SELECT id, prize, draw_date, draw_time, name FROM lottery_contests;");
        $stmt ->execute();
        $stmt ->bind_result($id,$prize, $draw_date, $draw_time ,$name);
        $stmt->store_result();
        $contests_list = array();
        if($stmt->num_rows>0){
            while($stmt ->fetch()){

                $temp = array();
                
                $temp['id'] = $id;
                $temp['prize'] = $prize;
                $temp['draw_date'] = $draw_date;
                $temp['draw_time'] = $draw_time;
                $temp['name'] = $name;

                array_push($contests_list,$temp);
    
           }
           return $contests_list;
        }
        else{
            return 0;
        }

    }
    public function getAllWithdrawalRequests(){
        $stmt = $this->con->prepare("SELECT withdrawal_requests.id, user_id, CONCAT_WS('', users.first_name,' ',users.middle_name,' ', users.last_name) as user_name ,users.email,bank_code,amount, date, status FROM users,withdrawal_requests WHERE users.id = withdrawal_requests.user_id;");
        $stmt ->execute();
        $stmt ->bind_result($id,$user_id, $user_name,$email,$bank_code,$amount,$date,$status);
        $stmt->store_result();
        $list = array();
        if($stmt->num_rows>0){
            while($stmt ->fetch()){

                $temp = array();
                
                $temp['id'] = $id;
                $temp['user_id'] = $user_id;
                $temp['user_name'] = $user_name;
                $temp['email'] = $email;
                $temp['bank_code'] = $bank_code;
                $temp['amount'] = $amount;
                $temp['date'] = $date;
                $temp['status'] = $status;
               
                array_push($list,$temp);
    
           }
           return $list;
        }
        else{
            return 0;
        }

    }
    public function getAllUsers(){
        $stmt = $this->con->prepare("SELECT id, CONCAT_WS('', first_name,' ',middle_name,' ', last_name) as user_name, email, points, balance, referral_code FROM users;");
        $stmt ->execute();
        $stmt ->bind_result($id,$user_name, $email, $points, $balance, $referral_code);
        $stmt->store_result();
        $users_list = array();
        if($stmt->num_rows > 0){
            while($stmt ->fetch()){

                $temp = array();
                
                $temp['id'] = $id;
                $temp['user_name'] = $user_name;
                $temp['email'] = $email;
                $temp['points'] = $points;
                $temp['balance'] = $balance;
                $temp['referral_code'] = $referral_code;
    
                array_push($users_list,$temp);

           }
           return $users_list;
        }else{
            return $stmt->num_rows;
        }
       

    }
/**
 * _ADMIN_ delete a user
 */
    public function deleteUser($user_id) 
    {
        $result = $this->deleteUserRecordsFromParticipants($user_id);
        if($result==1 or $result == 0){
            if($this->isUserIdExistInUsers($user_id)){
                $stmt = $this-> con->prepare("DELETE FROM `users` WHERE id = ?;");
                $stmt->bind_param("s",$user_id);
                if($stmt->execute()){
                    return 1;
                }else{
                    return 2;
                }
            }else{
                return 0;
            }
        }else{
            return 2;
        } 
    }
    public function deleteUserRecordsFromParticipants($user_id){
        if($this->isUserIdExistInParticipants($user_id)){
            $stmt = $this-> con->prepare("DELETE FROM `participants` WHERE user_id = ?;");
            $stmt->bind_param("s",$user_id);
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
    /**
     * Contest Functions 
     */
    public function isContestIdExistInParticipants($contest_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM participants WHERE contest_id = ? ;");
        $stmt->bind_param("s",$contest_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    public function isContestIdExist($contest_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM lottery_contests WHERE id = ?;");
        $stmt->bind_param("s",$contest_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }

    public function addLotteryContest($prize, $draw_date,$name,$draw_time) 
    {
        $stmt = $this-> con->prepare("INSERT INTO `lottery_contests` (`prize`, `draw_date`, `name` ,`draw_time`) VALUES (? , ?, ?, ?);");
        $stmt->bind_param("ssss",$prize, $draw_date, $name, $draw_time);
        if($stmt->execute()){
            return 1;
        }else{
            return 0;
        }
    } 

    public function deleteLotteryContest($contest_id) 
    {
        $result = $this->deleteContestRecordsFromParticipants($contest_id);
        if($result==1 or $result == 0){
            if($this->isContestIdExist($contest_id)){
                $stmt = $this-> con->prepare("DELETE FROM `lottery_contests` WHERE id = ?;");
                $stmt->bind_param("s",$contest_id);        
                if($stmt->execute()){
                    return 1;
                }else{
                    return 2;
                }
            }else{
                return 0;
            }
        }else{
            return 2;
        } 
    }

    public function deleteContestRecordsFromParticipants($contest_id){
        if($this->isContestIdExistInParticipants($contest_id)){
            $stmt = $this-> con->prepare("DELETE FROM `participants` WHERE contest_id = ?;");
            $stmt->bind_param("s",$contest_id);
            if($stmt->execute()){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 0;
        }
    }
    public function deleteRequest($request_id) 
    {
        if($this->isRequestIdExist($request_id)){
            $stmt = $this-> con->prepare("DELETE FROM `withdrawal_requests` WHERE id = ?;");
            $stmt->bind_param("s",$request_id);        
            if($stmt->execute()){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 0;
        }
        
    }
    public function deleteParticipant($participant_id) 
    {
        if($this->isParticipantIdExist($participant_id)){
            $stmt = $this-> con->prepare("DELETE FROM `participants` WHERE id = ?;");
            $stmt->bind_param("s",$participant_id);        
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
   * state: done , not_done 
     * 1: request state was changed to done successfully
     * 2: request state was changed  to not done successfully
     * 3: request state was not changed because alrady done 
     * 4: request state was not changed because alrady not done 
     * 0: request state was not changed because is not exist in withdrawal_requests table
     */
    public function editRequestState($request_id, $edit_request_state){
        if($this->isRequestIdExist($request_id)){
            if($edit_request_state == 'done'){
                if($this->isRequestAlreadyDone($request_id)){
                    return 3;
                }else{
                    $stmt = $this-> con->prepare("Update `withdrawal_requests` set status = 1 where id = ?;");
                    $stmt->bind_param("s",$request_id);
                    $stmt->execute();
                    return 1;
                }  
            }elseif($edit_request_state == 'not_done'){
                if($this->isRequestAlreadyNotDone($request_id)){
                    return 4;
                }else{
                    $stmt = $this-> con->prepare("Update `withdrawal_requests` set status = 0 where id = ?;");
                    $stmt->bind_param("s",$request_id);
                    $stmt->execute();
                    return 2;   
                }       
            }
         
        }else{
            return 0;
        }
    }
    public function isRequestIdExist($request_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM `withdrawal_requests` WHERE id = ? ;");
        $stmt->bind_param("s",$request_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    public function isParticipantIdExist($participant_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM `participants` WHERE id = ? ;");
        $stmt->bind_param("s",$participant_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    public function isRequestAlreadyDone($request_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM withdrawal_requests WHERE id = ? and status = 1;");
        $stmt->bind_param("s",$request_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }
    public function isRequestAlreadyNotDone($request_id) 
    {
        $stmt = $this-> con->prepare("SELECT * FROM withdrawal_requests WHERE id = ? and status = 0;");
        $stmt->bind_param("s",$request_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows() > 0;
    }

}
