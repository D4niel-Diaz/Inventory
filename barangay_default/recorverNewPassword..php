<?php 



include_once 'connection.php';


try{

  $check_username = $con->real_escape_string($_POST['check_username']);
  $check_number = $con->real_escape_string($_POST['check_number']);
  $contact_number = $con->real_escape_string($_POST['contact_number']);
  $new_password = $con->real_escape_string($_POST['new_password']);
  $new_confirm_password = $con->real_escape_string($_POST['new_confirm_password']);


  $sql_check = "SELECT contact_number, password FROM users WHERE username = ? ";
  $stmt_check = $con->prepare($sql_check) or die ($con->error);
  $stmt_check->bind_param('s',$check_username);
  $stmt_check->execute();
  $result_check = $stmt_check->get_result();
  $row_check = $result_check->fetch_assoc();

  if(strlen((string)$row_check['contact_number']) == 11){
    $check = $row_check['contact_number'][7] . $row_check['contact_number'][8] . $row_check['contact_number'][9] . $row_check['contact_number'][10];
    
  }else{
    $check =  $row_check['contact_number'][6] . $row_check['contact_number'][7] . $row_check['contact_number'][8] . $row_check['contact_number'][9];
  }

  if($contact_number != $check){
    exit('error');
  }

  if($new_password != $new_confirm_password){
    exit('error1');
  }



  $sql_update = "UPDATE users SET password = ? WHERE username = ?";
  $stmt_update = $con->prepare($sql_update) or die ($con->error);
  $stmt_update->bind_param('ss',$new_password,$check_username);
  $stmt_update->execute();
  $stmt_update->close();
 
  





}catch(Exception $e){
  echo $e->getMessage();
}








?>