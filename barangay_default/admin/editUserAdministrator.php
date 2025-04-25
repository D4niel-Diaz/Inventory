<?php 


include_once '../connection.php';


try{

  $user_id = $con->real_escape_string(trim($_POST['user_id']));
  $edit_first_name = $con->real_escape_string($_POST['edit_first_name']);
  $edit_middle_name = $con->real_escape_string($_POST['edit_middle_name']);
  $edit_last_name = $con->real_escape_string($_POST['edit_last_name']);
  $edit_username = $con->real_escape_string($_POST['edit_username']);
  $edit_password = $con->real_escape_string($_POST['edit_password']);
  $edit_contact_number = $con->real_escape_string($_POST['edit_contact_number']);
  $edit_image = $con->real_escape_string($_FILES['edit_image']['name']);

  $sql_check_admin = "SELECT * FROM users where id = ?";
  $stmt_check_admin = $con->prepare($sql_check_admin) or die ($con->error);
  $stmt_check_admin->bind_param('s',$user_id);
  $stmt_check_admin->execute();
  $result_check_admin = $stmt_check_admin->get_result();
  $row_check_admin = $result_check_admin->fetch_assoc();

  $old_first_name = $row_check_admin['first_name'];
  $old_middle_name = $row_check_admin['middle_name'];
  $old_last_name = $row_check_admin['last_name'];
  $old_username = $row_check_admin['username'];
  $old_password = $row_check_admin['password'];
  $old_contact_number = $row_check_admin['contact_number'];






  if(isset($edit_image)){

    
    $sql_check_image = "SELECT `image`, `image_path` FROM `users`  WHERE `id` = ?";
    $stmt_check_image = $con->prepare($sql_check_image) or die ($con->error);
    $stmt_check_image->bind_param('s',$user_id);
    $stmt_check_image->execute();
    $result_check_image = $stmt_check_image->get_result();
    $row_check_image = $result_check_image->fetch_assoc();
    $image_path = $row_check_image['image_path'];


    if($edit_image != ''){

      
      if($row_check_image['image'] != ''){
        unlink($image_path);
      }

      $type = explode('.', $edit_image);
      $type = $type[count($type) - 1];
      $new_image_name = uniqid(rand()) .'.'. $type;
      $new_image_path = '../assets/dist/img/' . $new_image_name;
      move_uploaded_file($_FILES['edit_image']['tmp_name'], $new_image_path);

    }else{

      $new_image_name = $row_check_image['image'];
      $new_image_path = $row_check_image['image_path'];

    }
  }

  $sql_check_username = "SELECT username FROM users WHERE username = ? AND id != ?";
  $stmt_check_username = $con->prepare($sql_check_username) or die ($con->error);
  $stmt_check_username->bind_param('ss',$edit_username,$user_id);
  $stmt_check_username->execute();
  $stmt_check_username->store_result();

  $count_username = $stmt_check_username->num_rows;
  if($count_username > 0){
    exit('error');
  }

  $sql_update = "UPDATE `users` SET first_name = ?, middle_name = ? , last_name = ? , username = ?, password = ?, contact_number = ?, image = ?, image_path = ? WHERE id = ?";
  $stmt = $con->prepare($sql_update) or die ($con->error);
  $stmt->bind_param('sssssssss',$edit_first_name,$edit_middle_name,$edit_last_name,$edit_username,$edit_password,$edit_contact_number,$new_image_name,$new_image_path,$user_id);
  $stmt->execute();
  $stmt->close();

  
  if($_POST['edit_first_name_check'] == 'true' || $_POST['edit_first_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED ADMINISTRATOR FIRST NAME  - '.' ' .$user_id.' |' .' '. ' FROM '.$old_first_name.' TO '. $edit_first_name;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_middle_name_check'] == 'true' || $_POST['edit_middle_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED ADMINISTRATOR MIDDLE NAME  - '.' ' .$user_id.' |' .' '. ' FROM '.$old_middle_name.' TO '. $edit_middle_name;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_last_name_check'] == 'true' || $_POST['edit_last_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED ADMINISTRATOR LAST NAME  - '.' ' .$user_id.' |' .' '. ' FROM '.$old_last_name.' TO '. $edit_last_name;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_username_check'] == 'true' || $_POST['edit_username_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED ADMINISTRATOR USERNAME  - '.' ' .$user_id.' |' .' '. ' FROM '.$old_username.' TO '. $edit_username;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_password_check'] == 'true' || $_POST['edit_password_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED ADMINISTRATOR PASSWORD  - '.' ' .$user_id.' |' .' '. ' FROM '.$old_password.' TO '. $edit_password;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  
  if($_POST['edit_contact_number_check'] == 'true' || $_POST['edit_contact_number_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED ADMINISTRATOR CONTACT NUMBER  - '.' ' .$user_id.' |' .' '. ' FROM '.$old_contact_number.' TO '. $edit_contact_number;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


}catch(Exception $e){
  echo $e->getMessage();
}




?>