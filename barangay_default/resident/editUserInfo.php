<?php 

include_once '../connection.php';

try{

  $user_id = $con->real_escape_string(trim($_POST['user_id']));;
  $username = $con->real_escape_string($_POST['username']);
  $current_password = $con->real_escape_string($_POST['current_password']);
  $retype_password = $con->real_escape_string($_POST['retype_password']);
  $password = $con->real_escape_string($_POST['new_password']);



    $sql_check_username = "SELECT username FROM users WHERE username = ? AND id != ?";
    $stmt_check_username = $con->prepare($sql_check_username) or die ($con->error);
    $stmt_check_username->bind_param('ss',$username,$user_id);
    $stmt_check_username->execute();
    $stmt_check_username->store_result();
    $count = $stmt_check_username->num_rows;

    if($count > 0){
      exit('errorUsername');
    }


    $sql_check_password = "SELECT password FROM users  WHERE id = ?";
    $stmt_check_password = $con->prepare($sql_check_password) or die ($con->error);
    $stmt_check_password->bind_param('s',$user_id);
    $stmt_check_password->execute();
    $result_password = $stmt_check_password->get_result();
    $row_password = $result_password->fetch_assoc();

    if($row_password['password'] != $current_password){
      exit('errorPassword');
    }


    if($password != $retype_password){
      exit('errorNot');
    }






  $sql_user = "UPDATE users SET username = ?, password = ? WHERE id = ?";
  $stmt_user = $con->prepare($sql_user) or die ($con->error);
  $stmt_user->bind_param('sss',$username,$password,$user_id);
  $stmt_user->execute();
  $stmt_user->close();





}catch(Exception $e){
  echo $e->getMessage();
}


?>