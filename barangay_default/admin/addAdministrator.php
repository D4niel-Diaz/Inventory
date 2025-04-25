<?php 

include_once '../connection.php';




try{


  $first_name = $con->real_escape_string($_POST['first_name']);
  $middle_name = $con->real_escape_string($_POST['middle_name']);
  $last_name = $con->real_escape_string($_POST['last_name']);
  $username = $con->real_escape_string($_POST['username']);
  $password = $con->real_escape_string($_POST['password']);
  $contact_number = $con->real_escape_string($_POST['contact_number']);
  $image = $con->real_escape_string($_FILES['image']['name']);

  date_default_timezone_set('Asia/Manila');
  $date = new DateTime();
  $uniqid = hexdec(uniqid()).$date->format("mdYHisv");
  $id = $uniqid;

  $user_type = 'secretary';


  if(isset($image)){
    if($image != '' || $image != null || !empty($image)){
      $type = explode('.',$image);
      $type = $type[count($type) -1];
      $new_image_name = uniqid(rand()) .'.'. $type;
      $new_image_path = '../assets/dist/img/' . $new_image_name;
      move_uploaded_file($_FILES['image']['tmp_name'],$new_image_path);
    }else{
      $new_image_name = '';
      $new_image_path = '';
    }
  }

  $sql_check_username = "SELECT username FROM users WHERE username = ?";
  $stmt_check_username = $con->prepare($sql_check_username) or die ($con->error);
  $stmt_check_username->bind_param('s',$username);
  $stmt_check_username->execute();
  $stmt_check_username->store_result();
  $count_check = $stmt_check_username->num_rows;
  if($count_check > 0){
    exit('error');
  }


  $sql = "INSERT INTO `users` (`id`,`first_name`,`middle_name`,`last_name`,`username`,`password`,`user_type`,`contact_number`,`image`,`image_path`)VALUES(?,?,?,?,?,?,?,?,?,?)";
  $stmt = $con->prepare($sql) or die ($con->error);
  $stmt->bind_param('ssssssssss',
    $id,
    $first_name,
    $middle_name,
    $last_name,
    $username,
    $password,
    $user_type,
    $contact_number,
    $new_image_name,
    $new_image_path
  );
  $stmt->execute();
  $stmt->close();

  $date_activity = $now = date("j-n-Y g:i A");  
  $admin = strtoupper('ADMIN').':' .' '. 'ADDED ADMINISTRATOR  - '.' ' .$id.' | ' . $first_name .' '. $last_name;
  $status_activity_log = 'delete';
  $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
  $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
  $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
  $stmt_activity_log->execute();
  $stmt_activity_log->close();


}catch(Exception $e){
  echo $e->getMessage();
}




?>