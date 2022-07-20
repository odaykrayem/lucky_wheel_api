<?php
  $stmt = $this-> con->prepare("SELECT prize FROM `lottery_contests` WHERE id = 31");
  $stmt->bind_param("s",$contest_id);
  $stmt->execute();
  $contest = $stmt->get_result()->fetch_assoc();  
  echo $contest['prize'];
  
  ?>