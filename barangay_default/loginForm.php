<?php 
session_start();
include_once 'connection.php';
include_once 'userInfo.php';


try{
  $username = $con->real_escape_string($_POST['username']);
$password = $con->real_escape_string(($_POST['password']));



$sql = "SELECT `id`,`username`, `password`, `user_type`, `first_name`, `middle_name`, `last_name` FROM `users` WHERE (username = ? OR id = ?)  ";
$stmt = $con->prepare($sql) or die ($con->error);
$stmt->bind_param('ss',$username,$username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $result->num_rows;

if($count > 0){
  $user_id = $row['id'];
  $checkUsername = $row['username'];
  $checkPassword = $row['password'];
  $fname = $row['first_name'];
  $mname = $row['middle_name'];
  $lname = $row['last_name'];
  $user_type = $row['user_type'];

    if($password == $checkPassword){

      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $checkUsername;
      $_SESSION['user_type'] = $user_type;

      date_default_timezone_set('Asia/Manila');
      $dates = new DateTime();
      $uniqid = uniqid(mt_rand().$dates->format("YmdHisv").rand());
      $generate = md5($uniqid);
      $rand = uniqid(rand()) . $generate;

      $date = date("Y l F j, h:i A");
      $device = UserInfo::get_device();
      $os = UserInfo::get_os();

      if($user_type == 'admin'){

        $sql_user = "SELECT first_name, last_name FROM users WHERE id = ?";
        $stmt_user = $con->prepare($sql_user) or die ($con->error);
        $stmt_user->bind_param('s',$_SESSION['user_id']);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $row_user = $result_user->fetch_assoc();
        $first_name = $row_user['first_name'];
        $last_name = $row_user['last_name'];
        $status_activity_log = 'login';

        $date_activity = $now = date("j-n-Y g:i A"); 
        $message =  'ADMIN'. ': '.$first_name.' '. $last_name .' | '. 'LOGIN';
        $sql_system_logs= "INSERT INTO activity_log (`message`, `date`,`status`) VALUES (?,?,?)";
        $query_system_logs = $con->prepare($sql_system_logs) or die ($con->error);
        $query_system_logs->bind_param('sss',$message,$date_activity,$status_activity_log);
        $query_system_logs->execute();
        $query_system_logs->close();

        exit('admin');
      }elseif($user_type == 'secretary'){

        $sql_user = "SELECT first_name, last_name FROM users WHERE id = ?";
        $stmt_user = $con->prepare($sql_user) or die ($con->error);
        $stmt_user->bind_param('s',$_SESSION['user_id']);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $row_user = $result_user->fetch_assoc();
        $first_name = $row_user['first_name'];
        $last_name = $row_user['last_name'];
        $status_activity_log = 'login';

        $date_activity = $now = date("j-n-Y g:i A"); 
        $message =  'OFFICIAL'. ': '.$first_name.' '. $last_name .' | '. 'LOGIN';
        $sql_system_logs= "INSERT INTO activity_log (`message`, `date`,`status`) VALUES (?,?,?)";
        $query_system_logs = $con->prepare($sql_system_logs) or die ($con->error);
        $query_system_logs->bind_param('sss',$message,$date_activity,$status_activity_log);
        $query_system_logs->execute();
        $query_system_logs->close();

        exit('secretary');
      }else{
        
        $sql_user = "SELECT first_name, last_name FROM users WHERE id = ?";
        $stmt_user = $con->prepare($sql_user) or die ($con->error);
        $stmt_user->bind_param('s',$_SESSION['user_id']);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $row_user = $result_user->fetch_assoc();
        $first_name = $row_user['first_name'];
        $last_name = $row_user['last_name'];
        $status_activity_log = 'login';

        $date_activity = $now = date("j-n-Y g:i A"); 
        $message =  'RESIDENT'. ': '.$first_name.' '. $last_name .' | '. 'LOGIN';
        $sql_system_logs= "INSERT INTO activity_log (`message`, `date`,`status`) VALUES (?,?,?)";
        $query_system_logs = $con->prepare($sql_system_logs) or die ($con->error);
        $query_system_logs->bind_param('sss',$message,$date_activity,$status_activity_log);
        $query_system_logs->execute();
        $query_system_logs->close();

        exit('resident');
      }

        
    }else{

      exit('errorPassword');

    }

}else{

  exit('errorUsername');

}

}catch(Exception $e){
  echo $e->getMessage();
}





?>