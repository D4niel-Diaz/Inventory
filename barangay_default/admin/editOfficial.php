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


   $edit_pwd = $con->real_escape_string($_POST['edit_pwd']);

   if($edit_pwd == 'YES'){
     $edit_pwd_info = $con->real_escape_string($_POST['edit_pwd_info']);
   }else{
    $edit_pwd_info = '';
   }


$official_id = $con->real_escape_string($_POST['official_id']);
$edit_single_parent = $con->real_escape_string($_POST['edit_single_parent']);
$edit_term_from = $con->real_escape_string($_POST['edit_term_from']);
$edit_term_to = $con->real_escape_string($_POST['edit_term_to']);
$edit_position = $con->real_escape_string($_POST['edit_position']);
$edit_voters = $con->real_escape_string($_POST['edit_voters']);
$edit_first_name = $con->real_escape_string($_POST['edit_first_name']);
$edit_middle_name = $con->real_escape_string($_POST['edit_middle_name']);
$edit_last_name = $con->real_escape_string($_POST['edit_last_name']);
$edit_suffix = $con->real_escape_string($_POST['edit_suffix']);
$edit_gender = $con->real_escape_string($_POST['edit_gender']);
$edit_civil_status = $con->real_escape_string($_POST['edit_civil_status']);
$edit_religion = $con->real_escape_string($_POST['edit_religion']);
$edit_nationality = $con->real_escape_string($_POST['edit_nationality']);
$edit_contact_number = $con->real_escape_string($_POST['edit_contact_number']);
$edit_email_address = $con->real_escape_string($_POST['edit_email_address']);
$edit_address = $con->real_escape_string($_POST['edit_address']);
$edit_birth_date = $con->real_escape_string($_POST['edit_birth_date']);
$edit_birth_place = $con->real_escape_string($_POST['edit_birth_place']);
$edit_municipality = $con->real_escape_string($_POST['edit_municipality']);
$edit_zip = $con->real_escape_string($_POST['edit_zip']);
$edit_barangay = $con->real_escape_string($_POST['edit_barangay']);
$edit_house_number = $con->real_escape_string($_POST['edit_house_number']);
$edit_street = $con->real_escape_string($_POST['edit_street']);
$edit_fathers_name = $con->real_escape_string($_POST['edit_fathers_name']);
$edit_mothers_name = $con->real_escape_string($_POST['edit_mothers_name']);
$edit_guardian = $con->real_escape_string($_POST['edit_guardian']);
$edit_guardian_contact = $con->real_escape_string($_POST['edit_guardian_contact']);
$edit_image = $con->real_escape_string($_FILES['edit_image']['name']);


$sql_check_official = "SELECT official_status.*, official_information.*, position.position AS position_row FROM official_status 
INNER JOIN official_information ON official_status.official_id = official_information.official_id 
INNER JOIN position ON official_status.position = position.position_id
WHERE official_status.official_id = ?";
$stmt_check_official = $con->prepare($sql_check_official) or die ($con->error);
$stmt_check_official->bind_param('s',$official_id);
$stmt_check_official->execute();
$result_check_official = $stmt_check_official->get_result();
$row_check_offical = $result_check_official->fetch_assoc();

$old_first_name = $row_check_offical['first_name'];
$old_middle_name = $row_check_offical['middle_name'];
$old_last_name = $row_check_offical['last_name'];
$old_term_from = $row_check_offical['term_from'];
$old_term_to = $row_check_offical['term_to'];
$old_voters = $row_check_offical['voters'];
$old_pwd = $row_check_offical['pwd'];
$old_birth_date = $row_check_offical['birth_date'];
$old_birth_place = $row_check_offical['birth_place'];
$old_suffix = $row_check_offical['suffix'];
$old_gender = $row_check_offical['gender'];
$old_civil_status = $row_check_offical['civil_status'];
$old_religion = $row_check_offical['religion'];
$old_nationality = $row_check_offical['nationality'];
$old_municipality = $row_check_offical['municipality'];
$old_zip = $row_check_offical['zip'];
$old_barangay = $row_check_offical['barangay'];
$old_house_number = $row_check_offical['house_number'];
$old_street = $row_check_offical['street'];
$old_address = $row_check_offical['address'];
$old_email_address = $row_check_offical['email_address'];
$old_contact_number = $row_check_offical['contact_number'];
$old_fathers_name = $row_check_offical['fathers_name'];
$old_mothers_name = $row_check_offical['mothers_name'];
$old_guardian = $row_check_offical['guardian'];
$old_guardian_contact = $row_check_offical['guardian_contact'];
$old_pwd_info = $row_check_offical['pwd_info'];
$old_position = strtoupper($row_check_offical['position_row']);






if(isset($edit_image)){

  $sql_check_image = "SELECT `image`, `image_path` FROM `official_information`  WHERE `official_id` = ?";
  $stmt_check_image = $con->prepare($sql_check_image) or die ($con->error);
  $stmt_check_image->bind_param('s',$official_id);
  $stmt_check_image->execute();
  $result_check_image = $stmt_check_image->get_result();
  $row_check_image = $result_check_image->fetch_assoc();
  $image_path = $row_check_image['image_path'];


  if($edit_image != '' || $edit_image != null || !empty($edit_image)){

    if($row_check_image['image'] != '' || $row_check_image['image'] != null || !empty($row_check_image['image'])){
      unlink($image_path);
    }


    $type = explode('.', $edit_image);
    $type = $type[count($type) - 1];
    $new_edit_image_name = uniqid(rand()) .'.'. $type;
    $new_edit_image_path = '../assets/dist/img/' . $new_edit_image_name;
    move_uploaded_file($_FILES['edit_image']['tmp_name'], $new_edit_image_path);
    
  }else{
    $new_edit_image_name = $row_check_image['image'];
    $new_edit_image_path = $row_check_image['image_path'];
  }
}





$sql_position = "SELECT COUNT(position) AS official_position FROM official_status WHERE position = ? AND official_id != ? ";
$stmt_position = $con->prepare($sql_position) or die ($con->error);
$stmt_position->bind_param('ss',$edit_position,$official_id);
$stmt_position->execute();
$result_position = $stmt_position->get_result();
$row_position = $result_position->fetch_assoc();


$sql_position_limit = "SELECT position_limit, position FROM position WHERE position_id = ?";
$stmt_position_limit = $con->prepare($sql_position_limit) or die ($con->error);
$stmt_position_limit->bind_param('s',$edit_position);
$stmt_position_limit->execute();
$result_position_limit = $stmt_position_limit->get_result();
$row_position_limit = $result_position_limit->fetch_assoc();
$new_position = strtoupper($row_position_limit['position']);

if($row_position_limit['position_limit'] == $row_position['official_position']){
  exit('error');
}




date_default_timezone_set('Asia/Manila');

$today = date("Y/m/d");
$age = date_diff(date_create($edit_birth_date), date_create($today));
$edit_age_date = $age->format("%y");


if($edit_age_date >= '60'){
  $senior = 'YES';
}else{
  $senior = 'NO';
}



$sql_official = "UPDATE `official_information` SET 
`first_name`= ?,
`middle_name`= ?,
`last_name`= ?,
`age`= ?,
`suffix`= ?,
`gender`= ?,
`civil_status`= ?,
`religion`= ?,
`nationality`= ?,
`contact_number`= ?,
`email_address`= ?,
`address`= ?,
`birth_date`= ?,
`birth_place`= ?,
`municipality`= ?,
`zip`= ?,
`barangay`= ?,
`house_number`= ?,
`street`= ?,
`fathers_name`= ?,
`mothers_name`= ?,
`guardian`= ?,
`guardian_contact`= ?,
`image`= ?,
`image_path`= ? 
WHERE  `official_id` = ? ";
$stmt_official = $con->prepare($sql_official) or die ($con->error);
$stmt_official->bind_param('ssssssssssssssssssssssssss',
    $edit_first_name,
    $edit_middle_name,
    $edit_last_name,
    $edit_age_date,
    $edit_suffix,
    $edit_gender,
    $edit_civil_status,
    $edit_religion,
    $edit_nationality,
    $edit_contact_number,
    $edit_email_address,
    $edit_address,
    $edit_birth_date,
    $edit_birth_place,
    $edit_municipality,
    $edit_zip,
    $edit_barangay,
    $edit_house_number,
    $edit_street,
    $edit_fathers_name,
    $edit_mothers_name,
    $edit_guardian,
    $edit_guardian_contact,
    $new_edit_image_name,
    $new_edit_image_path,
    $official_id
);

$stmt_official->execute();
$stmt_official->close();



  $sql_edit_official_status = "UPDATE `official_status` SET `voters` = ?, `senior` = ?, `pwd` = ?, `pwd_info` = ?, `single_parent` = ?, `term_from` = ?, `term_to` = ?, `position` = ? WHERE `official_id` = ?";
  $stmt_edit_official_status = $con->prepare($sql_edit_official_status) or die ($con->error);
  $stmt_edit_official_status->bind_param('sssssssss',$edit_voters,$senior,$edit_pwd,$edit_pwd_info,$edit_single_parent,$edit_term_from,$edit_term_to,$edit_position,$official_id);
  $stmt_edit_official_status->execute();
  $stmt_edit_official_status->close();




  if($_POST['edit_first_name_check'] == 'true' || $_POST['edit_first_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL - FIRST NAME '.' ' .$official_id.' |' .' '. ' FROM '.$old_first_name.' TO '. $edit_first_name;
    $status_activity_log = 'update';
  
  
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_pwd_info_check'] == 'true' || $_POST['edit_pwd_info_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL - PWD TYPE  '.' ' .$official_id.' |' .' '. ' FROM '.$old_pwd_info.' TO '. $edit_pwd_info;
    $status_activity_log = 'update';
  
  
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_last_name_check'] == 'true' || $_POST['edit_last_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL LAST NAME- '.' ' .$official_id.' |' .' '. ' FROM '.$old_last_name.' TO '. $edit_last_name;
    $status_activity_log = 'update';
  
  
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  
  if($_POST['edit_position_check'] == 'true' || $_POST['edit_position_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL POSITION - '.' ' .$official_id.' |' .' '. ' FROM '.$old_position.' TO '. $new_position;
    $status_activity_log = 'update';
  
  
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_term_from_check'] == 'true' || $_POST['edit_term_from_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL START - '.' ' .$official_id.' |' .' '. ' FROM '.$old_term_from.' TO '. $edit_term_from;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  
  if($_POST['edit_term_to_check'] == 'true' || $_POST['edit_term_to_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL END - '.' ' .$official_id.' |' .' '. ' FROM '.$old_term_to.' TO '. $edit_term_to;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  
  if($_POST['edit_voters_check'] == 'true' || $_POST['edit_voters_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL VOTERS - '.' ' .$official_id.' |' .' '. ' FROM '.$old_voters.' TO '. $edit_voters;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_pwd_check'] == 'true' || $_POST['edit_pwd_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL PWD - '.' ' .$official_id.' |' .' '. ' FROM '.$old_pwd.' TO '. $edit_pwd;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_birth_date_check'] == 'true' || $_POST['edit_birth_date_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL BIRTH DATE - '.' ' .$official_id.' |' .' '. ' FROM '.$old_birth_date.' TO '. $edit_birth_date;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_birth_place_check'] == 'true' || $_POST['edit_birth_place_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL BIRTH PLACE - '.' ' .$official_id.' |' .' '. ' FROM '.$old_birth_place.' TO '. $edit_birth_place;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_middle_name_check'] == 'true' || $_POST['edit_middle_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL MIDDLE NAME - '.' ' .$official_id.' |' .' '. ' FROM '.$old_middle_name.' TO '. $edit_middle_name;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_suffix_check'] == 'true' || $_POST['edit_suffix_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL SUFFIX - '.' ' .$official_id.' |' .' '. ' FROM '.$old_suffix.' TO '. $edit_suffix;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_gender_check'] == 'true' || $_POST['edit_gender_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL GENDER - '.' ' .$official_id.' |' .' '. ' FROM '.$old_gender.' TO '. $edit_gender;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_civil_status_check'] == 'true' || $_POST['edit_civil_status_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL CIVIL STATUS - '.' ' .$official_id.' |' .' '. ' FROM '.$old_civil_status.' TO '. $edit_civil_status;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_religion_check'] == 'true' || $_POST['edit_religion_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL RELIGION - '.' ' .$official_id.' |' .' '. ' FROM '.$old_religion.' TO '. $edit_religion;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_nationality_check'] == 'true' || $_POST['edit_nationality_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL NATIONALITY - '.' ' .$official_id.' |' .' '. ' FROM '.$old_nationality.' TO '. $edit_nationality;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_municipality_check'] == 'true' || $_POST['edit_municipality_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL MUNICPALITY - '.' ' .$official_id.' |' .' '. ' FROM '.$old_municipality.' TO '. $edit_municipality;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_zip_check'] == 'true' || $_POST['edit_zip_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL ZIP - '.' ' .$official_id.' |' .' '. ' FROM '.$old_zip.' TO '. $edit_zip;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_barangay_check'] == 'true' || $_POST['edit_barangay_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL BARANGAY - '.' ' .$official_id.' |' .' '. ' FROM '.$old_barangay.' TO '. $edit_barangay;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_house_number_check'] == 'true' || $_POST['edit_house_number_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL HOUSE NUMBER - '.' ' .$official_id.' |' .' '. ' FROM '.$old_house_number.' TO '. $edit_house_number;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_street_check'] == 'true' || $_POST['edit_street_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL STREET - '.' ' .$official_id.' |' .' '. ' FROM '.$old_street.' TO '. $edit_street;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_address_check'] == 'true' || $_POST['edit_address_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL ADDRESS - '.' ' .$official_id.' |' .' '. ' FROM '.$old_address.' TO '. $edit_address;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_email_address_check'] == 'true' || $_POST['edit_email_address_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL EMAIL ADDRESS - '.' ' .$official_id.' |' .' '. ' FROM '.$old_email_address.' TO '. $edit_email_address;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_contact_number_check'] == 'true' || $_POST['edit_contact_number_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL CONTACT NUMBER - '.' ' .$official_id.' |' .' '. ' FROM '.$old_contact_number.' TO '. $edit_contact_number;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_fathers_name_check'] == 'true' || $_POST['edit_fathers_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL FATHERS NAME  - '.' ' .$official_id.' |' .' '. ' FROM '.$old_fathers_name.' TO '. $edit_fathers_name;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }

  if($_POST['edit_mothers_name_check'] == 'true' || $_POST['edit_mothers_name_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL MOTHERS NAME  - '.' ' .$official_id.' |' .' '. ' FROM '.$old_mothers_name.' TO '. $edit_mothers_name;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_guardian_check'] == 'true' || $_POST['edit_guardian_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL GUARDIAN  - '.' ' .$official_id.' |' .' '. ' FROM '.$old_guardian.' TO '. $edit_guardian;
    $status_activity_log = 'update';
    $sql_activity_log = "INSERT INTO activity_log (`message`,`date`,`status`)VALUES(?,?,?)";
    $stmt_activity_log = $con->prepare($sql_activity_log) or die ($con->error);
    $stmt_activity_log->bind_param('sss',$admin,$date_activity,$status_activity_log);
    $stmt_activity_log->execute();
    $stmt_activity_log->close();
    
  
  }


  if($_POST['edit_guardian_contact_check'] == 'true' || $_POST['edit_guardian_contact_check'] === TRUE){

  
    $date_activity = $now = date("j-n-Y g:i A");  
    $admin = strtoupper('ADMIN').':' .' '. 'UPDATED OFFICIAL GUARDIAN CONTACT  - '.' ' .$official_id.' |' .' '. ' FROM '.$old_guardian_contact.' TO '. $edit_guardian_contact;
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