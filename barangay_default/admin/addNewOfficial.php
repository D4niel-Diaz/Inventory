<?php 

include_once '../connection.php';
session_start();

try{
  if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'){

    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
    $stmt_user = $con->prepare($sql_user) or die ($con->error);
    $stmt_user->bind_param('s',$user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
  }else{
    echo '<script>
           window.location.href = "../login.php";
         </script>';
   }
   if(isset($_POST['add_pwd_info'])){
    $add_pwd_check = $con->real_escape_string($_POST['add_pwd_info']);
  }else{
    $add_pwd_check = '';
  }
  
  $add_single_parent = $con->real_escape_string($_POST['add_single_parent']);
$add_pwd = $con->real_escape_string($_POST['add_pwd']);
$add_term_from = $con->real_escape_string($_POST['add_term_from']);
$add_term_to = $con->real_escape_string($_POST['add_term_to']);
$add_position = $con->real_escape_string($_POST['add_position']);
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
$add_approval = 'ACCEPTED';



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

$sql_position = "SELECT COUNT(position) AS count_position  FROM official_status WHERE position = ?";
$stmt_position = $con->prepare($sql_position) or die ($con->error);
$stmt_position->bind_param('s',$add_position);
$stmt_position->execute();
$result_position = $stmt_position->get_result();
$row_position = $result_position->fetch_assoc();

$sql_limit_position = "SELECT position_limit, position FROM position WHERE position_id = ?";
$stmt_position_limit = $con->prepare($sql_limit_position) or die ($con->error);
$stmt_position_limit->bind_param('s',$add_position);
$stmt_position_limit->execute();
$result_position_limit = $stmt_position_limit->get_result();
$row_position_limit = $result_position_limit->fetch_assoc();

if($row_position_limit['position_limit'] == $row_position['count_position']){
  exit('error');
}







date_default_timezone_set('Asia/Manila');
$date = new DateTime();

$today = date("Y/m/d");
$age = date_diff(date_create($add_birth_date), date_create($today));
$add_age_date = $age->format("%y");


$official_id = $date->format("mdYHisv").$add_age_date;
$date_added = date("m/d/Y h:i A");


if($add_age_date >= '60'){
  $senior = 'YES';
}else{
  $senior = 'NO';
}



  $sql = "INSERT INTO `official_information`
  (`official_id`,
   `first_name`, 
   `middle_name`, 
   `last_name`, 
   `gender`,
   `suffix`, 
   `birth_date`, 
   `birth_place`, 
   `age`, 
   `civil_status`, 
   `religion`, 
   `nationality`, 
   `municipality`, 
   `zip`, 
   `barangay`, 
   `house_number`, 
   `street`, 
   `address`, 
   `email_address`, 
   `contact_number`, 
   `fathers_name`, 
   `mothers_name`, 
   `guardian`, 
   `guardian_contact`, 
   `image`, 
   `image_path`
   ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt = $con->prepare($sql) or die ($con->error);
  $stmt->bind_param('ssssssssssssssssssssssssss',
    $official_id,
    $add_first_name,
    $add_middle_name,
    $add_last_name,
    $add_gender,
    $add_suffix,
    $add_birth_date,
    $add_birth_place,
    $add_age_date,
    $add_civil_status,
    $add_religion,
    $add_nationality,
    $add_municipality,
    $add_zip,
    $add_barangay,
    $add_house_number,
    $add_street,
    $add_address,
    $add_email_address,
    $add_contact_number,
    $add_fathers_name,
    $add_mothers_name,
    $add_guardian,
    $add_guardian_contact,
    $new_image_name,
    $new_image_path
  );
  $stmt->execute();
  $stmt->close();
  
  $sql_official_status = "INSERT INTO `official_status` (`official_id`, `status`, `senior`,`voters`, `position`,`date_added`, `term_from`, `term_to`, `pwd`,`pwd_info`,`single_parent`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
  $stmt_official_status = $con->prepare($sql_official_status) or die ($con->error);
  $stmt_official_status->bind_param('sssssssssss',$official_id,$add_status,$senior,$add_voters,$add_position,$date_added,$add_term_from,$add_term_to,$add_pwd,$add_pwd_check,$add_single_parent);
  $stmt_official_status->execute();
  $stmt_official_status->close();

  

  
  $date_activity = $now = date("j-n-Y g:i A");  
  $activity_log_position = strtoupper($row_position_limit['position']);
  $admin = strtoupper('ADMIN').':' .' '. 'ADDED OFFICIAL -'.' ' .$official_id.' |' .' '.$activity_log_position .' '.$add_first_name .' '. $add_last_name .' '. $add_suffix .' | START ' .$add_term_from .' END ' .$add_term_to;
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