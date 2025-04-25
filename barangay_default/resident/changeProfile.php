<?php 

include_once '../connection.php';
session_start();

try{



  if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'resident'){

    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
    $stmt_user = $con->prepare($sql_user) or die ($con->error);
    $stmt_user->bind_param('s',$user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $username = $row_user['username'];
    $old_password_user = $row_user['password'];
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];



    $username = $con->real_escape_string($_POST['username']);
    $old_password = $con->real_escape_string($_POST['old_password']);
    $new_password = $con->real_escape_string($_POST['new_password']);
    $edit_confirm_password = $con->real_escape_string($_POST['edit_confirm_password']);


    $sql_username = "SELECT * FROM users WHERE username = ? AND id != ?";
    $query_username = $con->prepare($sql_username) or die ($con->error);
    $query_username->bind_param('ss',$username,$user_id);
    $query_username->execute();
    $count_username = $query_username->get_result();
    $check_username = $count_username->num_rows;


    if($check_username > 0){
      exit('error1');
    }

    if($old_password_user != $old_password){
      exit('error2');
    }


    if($new_password == '' && $edit_confirm_password == ''){
      $pass = $old_password;
    }else{
      $pass = $edit_confirm_password;
    }
    

    $sql_update = "UPDATE `users` SET  username = ?, password = ? WHERE id = ?";
    $stmt = $con->prepare($sql_update) or die ($con->error);
    $stmt->bind_param('sss',$username,$pass,$user_id);
    $stmt->execute();
    $stmt->close();








   


  }else{
   echo '<script>
          window.location.href = "../login.php";
        </script>';
  }






}catch(Exception $e){
  echo $e->getMessage();
}






?>