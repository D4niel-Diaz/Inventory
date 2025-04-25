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


date_default_timezone_set('Asia/Manila');
$date = new DateTime();
$number = rand($date->format("mdyHisv"),true);


$date_added = date("m/d/Y h:i A");
$archive = 'NO';


if(isset($_POST['add_pwd_info'])){
  $add_pwd_check = $con->real_escape_string($_POST['add_pwd_info']);
}else{
  $add_pwd_check = '';
}




$add_single_parent = $con->real_escape_string($_POST['add_single_parent']);
$add_pwd = $con->real_escape_string($_POST['add_pwd']);
$add_voters = $con->real_escape_string($_POST['add_voters']);
$add_first_name = $con->real_escape_string($_POST['add_first_name']);
$add_middle_name = $con->real_escape_string($_POST['add_middle_name']);
$add_last_name = $con->real_escape_string($_POST['add_last_name']);

$add_suffix = $con->real_escape_string($_POST['add_suffix']);
$add_gender = $con->real_escape_string($_POST['add_gender']);
$add_civil_status = $con->real_escape_string($_POST['add_civil_status']);
$add_religion = $con->real_escape_string($_POST['add_religion']);
$add_nationality = $con->real_escape_string($_POST['add_nationality']);
$add_contact_number = $con->real_escape_string($_POST['add_contact_number']);
$add_email_address = $con->real_escape_string($_POST['add_email_address']);
$add_address = $con->real_escape_string($_POST['add_address']);
$add_birth_date = $con->real_escape_string($_POST['add_birth_date']);
$add_birth_place = $con->real_escape_string($_POST['add_birth_place']);
$add_municipality = $con->real_escape_string($_POST['add_municipality']);
$add_zip = $con->real_escape_string($_POST['add_zip']);
$add_barangay = $con->real_escape_string($_POST['add_barangay']);
$add_house_number = $con->real_escape_string($_POST['add_house_number']);
$add_street = $con->real_escape_string($_POST['add_street']);
$add_fathers_name = $con->real_escape_string($_POST['add_fathers_name']);
$add_mothers_name = $con->real_escape_string($_POST['add_mothers_name']);
$add_guardian = $con->real_escape_string($_POST['add_guardian']);
$add_guardian_contact = $con->real_escape_string($_POST['add_guardian_contact']);
$add_image = $con->real_escape_string($_FILES['add_image']['name']);
$add_status = 'ACTIVE';


$user_type = 'resident';

$password = $date->format("mdYHisv");






if(isset($add_image)){
  if($add_image != '' || $add_image != null || !empty($add_image)){
    $type = explode('.', $add_image);
    $type = $type[count($type) -1];
    $new_image_name = uniqid(rand()) .'.'. $type;
    $new_image_path = '../assets/dist/img/' . $new_image_name;
    move_uploaded_file($_FILES['add_image']['tmp_name'],$new_image_path);
  }else{
    $new_image_name = '';
    $new_image_path = '';
  }
}



$today = date("Y/m/d");
$age = date_diff(date_create($add_birth_date), date_create($today));
$add_age_date = $age->format("%y");

if($add_age_date >= '60'){
  $senior = 'YES';
}else{
  $senior = 'NO';
}

if($add_age_date == '0'){
  $age_add = '';
}else{
  $age_add = $add_age_date;
}



$sql = "INSERT INTO `residence_information`(
  `residence_id`, 
  `first_name`, 
  `middle_name`, 
  `last_name`, 
  `age`, 
  `suffix`, 
  `gender`, 
  `civil_status`, 
  `religion`, 
  `nationality`, 
  `contact_number`, 
  `email_address`, 
  `address`, 
  `birth_date`, 
  `birth_place`, 
  `municipality`, 
  `zip`, 
  `barangay`, 
  `house_number`, 
  `street`, 
  `fathers_name`, 
  `mothers_name`, 
  `guardian`, 
  `guardian_contact`,
  `image`,
  `image_path`
  ) 
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $con->prepare($sql) or die ($con->error);
$stmt->bind_param('ssssssssssssssssssssssssss',
  $number,
  $add_first_name,
  $add_middle_name,
  $add_last_name,
  $age_add,
  $add_suffix,
  $add_gender,
  $add_civil_status,
  $add_religion,
  $add_nationality,
  $add_contact_number,
  $add_email_address,
  $add_address,
  $add_birth_date,
  $add_birth_place,
  $add_municipality,
  $add_zip,
  $add_barangay,
  $add_house_number,
  $add_street,
  $add_fathers_name,
  $add_mothers_name,
  $add_guardian,
  $add_guardian_contact,
  $new_image_name,
  $new_image_path
);
$stmt->execute();
$stmt->close();

$sql_residence_status = "INSERT INTO `residence_status` (`residence_id`, `status`, `voters`,`archive`,`pwd`,`pwd_info`,`single_parent`,`senior`, `date_added`) VALUES (?,?,?,?,?,?,?,?,?)";
$stmt_residence_status = $con->prepare($sql_residence_status) or die ($con->error);
$stmt_residence_status->bind_param('sssssssss',$number,$add_status,$add_voters,$archive,$add_pwd,$add_pwd_check,$add_single_parent,$senior,$date_added);
$stmt_residence_status->execute();
$stmt_residence_status->close();


$sql_add_user = "INSERT INTO `users`(`id`, `first_name`, `middle_name`, `last_name`, `username`, `password`, `user_type`, `contact_number`,`image`,`image_path`) VALUES (?,?,?,?,?,?,?,?,?,?)";
$stmt_user = $con->prepare($sql_add_user) or die ($con->error);
$stmt_user->bind_param('ssssssssss',$number,$add_first_name,$add_middle_name,$add_last_name,$number,$password,$user_type,$add_contact_number,$new_image_name,$new_image_path);
$stmt_user->execute();
$stmt_user->close();



$date_activity = $now = date("j-n-Y g:i A");  
  $admin = strtoupper('OFFICAL').': ' .$first_name_user.' '.$last_name_user. ' - ' .$user_id.' | '. 'ADDED RESIDENT -'.' ' .$number.' |' .'  '.$add_first_name .' '. $add_last_name .' '. $add_suffix;
  $status_activity_log = 'create';


  $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
  $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
  $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
  $stmt_activity_log->execute();
  $stmt_activity_log->close();
  



}catch(Exception $e){
  echo $e->getMessage();
}



?>