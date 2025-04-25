<?php 


include_once '../connection.php';
session_start();

 
  if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'secretary'){
  
    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
    $stmt_user = $con->prepare($sql_user) or die ($con->error);
    $stmt_user->bind_param('s',$user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];
  
  


  
  
  }else{
   echo '<script>
          window.location.href = "../login.php";
        </script>';
  }


try{


  if(isset($_REQUEST['id'])){
    $blotter_id = $con->real_escape_string($_REQUEST['id']);

    $sql_blotter = "SELECT * FROM blotter_record WHERE blotter_id IN ($blotter_id)";
    $stmt_blotter = $con->prepare($sql_blotter) or die ($con->error);
    $stmt_blotter->execute();
    $result_blotter = $stmt_blotter->get_result();
    $row_blotter = $result_blotter->fetch_assoc();

    $old_date_incident = $row_blotter['date_incident'];
    $old_date_reported = $row_blotter['date_reported'];
    $old_location_incident = $row_blotter['location_incident'];

    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('OFFICAL').': ' .$first_name_user.' '.$last_name_user. ' - ' .$user_id.' | '.  'DELETED BLOTTER RECORD - '.' ' .$blotter_id.' | ' . $old_date_incident.' ' . $old_date_reported. ' ' . $old_location_incident;
    $status_activity_log = 'delete';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
 

    $sql_delete_record = "DELETE FROM blotter_record WHERE blotter_id IN ($blotter_id)";
    $stmt_delete_record = $con->query($sql_delete_record) or die ($con->error);
 

    $sql_record_complainant = "DELETE FROM blotter_complainant WHERE blotter_main IN ($blotter_id)";
    $stmt_record_complainant = $con->query($sql_record_complainant) or die ($con->error);



    $sql_blotter_person = "DELETE FROM blotter_status WHERE blotter_main IN ($blotter_id)";
    $stmt_blotter_person = $con->query($sql_blotter_person) or die ($con->error);


  }


}catch(Exception $e){
  echo $e->getMessage();
}




?>