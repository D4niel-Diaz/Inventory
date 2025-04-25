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
  $username_user = $row_user['username'];
  $password_user = $row_user['password'];
  $first_name_user = $row_user['first_name'];
  $last_name_user = $row_user['last_name'];
  $user_type = $row_user['user_type'];
  $user_image = $row_user['image'];
  $user_image_path = $row_user['image_path'];








}else{
 echo '<script>
        window.location.href = "../login.php";
      </script>';
}

try{

  $username = $con->real_escape_string($_POST['username']);
  $first_name = $con->real_escape_string($_POST['first_name']);
  $middle_name = $con->real_escape_string($_POST['middle_name']);
  $last_name = $con->real_escape_string($_POST['last_name']);
  $contact_number = $con->real_escape_string($_POST['contact_number']);
  $old_password = $con->real_escape_string($_POST['old_password']);
  $new_password = $con->real_escape_string($_POST['new_password']);
  $image = $con->real_escape_string($_FILES['image']['name']);
  
  $new_confirm_password = $con->real_escape_string($_POST['new_confirm_password']);


  if(isset($image)){


    $sql_check_image_user = "SELECT `image`, `image_path` FROM `users`  WHERE `id` = ?";
    $stmt_check_image_user = $con->prepare($sql_check_image_user) or die ($con->error);
    $stmt_check_image_user->bind_param('s',$user_id);
    $stmt_check_image_user->execute();
    $result_check_image_user = $stmt_check_image_user->get_result();
    $row_check_image_user = $result_check_image_user->fetch_assoc();
    $image_name_user = $row_check_image_user['image'];
    $image_path_user = $row_check_image_user['image_path'];

    
    if($image != ''){



      if($row_check_image_user['image'] != '' || $row_check_image_user['image'] != null || !empty($row_check_image_user['image'])){
        unlink($image_path_user);
      }
  

      $type = explode('.',$image);
      $type = $type[count($type) -1];
      $new_image_name = uniqid(rand()) .'.'. $type;
      $new_image_path = '../assets/dist/img/' . $new_image_name;
      move_uploaded_file($_FILES['image']['tmp_name'],$new_image_path);

    }else{
      $new_image_name = $image_name_user;
      $new_image_path = $image_path_user;
    }

  }

  $sql_check_username = "SELECT username FROM users WHERE username = ? AND id != ?";
  $stmt_check_username = $con->prepare($sql_check_username) or die ($con->error);
  $stmt_check_username->bind_param('ss',$username,$user_id);
  $stmt_check_username->execute();
  $result_check_username = $stmt_check_username->get_result();
  $count_check_username = $result_check_username->num_rows;

  if($count_check_username > 0){
    exit('error');
  }

  if($old_password != $password_user){
    exit('error1');
  }

  if($new_password != $new_confirm_password){
    exit('error2');
  }


  if($new_password == '' && $new_confirm_password == ''){
    $pass = $old_password;
  }else{
    $pass = $new_confirm_password;
  }

  $sql_update = "UPDATE users SET username = ?, password = ?, first_name = ?, middle_name =? , last_name = ?, contact_number = ?, image = ?, image_path = ? WHERE id = ?";
  $stmt_update = $con->prepare($sql_update) or die ($con->error);
  $stmt_update->bind_param('sssssssss',$username,$pass,$first_name,$middle_name,$last_name,$contact_number,$new_image_name,$new_image_path,$user_id);
  $stmt_update->execute();
  $stmt_update->close();




}catch(Exception $e){
  echo $e->getMessage();
}


?>