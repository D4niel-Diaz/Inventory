<?php  

include_once '../connection.php';


$id = $con->real_escape_string($_POST['id']);
$barangay = $con->real_escape_string($_POST['barangay']);
$zone = $con->real_escape_string($_POST['zone']);
$district = $con->real_escape_string($_POST['district']);
$address = $con->real_escape_string($_POST['address']);
$postal_address = $con->real_escape_string($_POST['postal_address']);
$image = $con->real_escape_string($_FILES['add_image']['name']);


if(isset($image)){

    $sql = "SELECT `id`,`image`,`image_path` FROM `barangay_information` WHERE `id` = ?";
    $stmt = $con->prepare($sql) or die ($con->error);
    $stmt->bind_param('s',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $old_image = $row['image'];
    $old_image_path = $row['image_path'];

  if($image != '' || $image != null || !empty($image)){

    if($old_image != '' || $old_image != null || !empty($old_image)){
      unlink($old_image_path);
    }

    $type = explode('.',$image);
    $type = $type[count($type) -1]; 
    $new_image_name = uniqid(rand()) .'.'. $type;
    $new_image_path = '../assets/dist/img/' . $new_image_name;
    move_uploaded_file($_FILES['add_image']['tmp_name'], $new_image_path);

  }else{
    $new_image_name = $old_image;
    $new_image_path = $old_image_path;
  }

  
  
  $sql_insert = "UPDATE  `barangay_information` SET `barangay` = ?, `zone` = ?, `district` = ?, `image` = ?, `image_path` = ?, `address` = ?, `postal_address` = ? WHERE `id` = ?";
  $stmt_insert = $con->prepare($sql_insert) or die ($con->error);
  $stmt_insert->bind_param('ssssssss',$barangay,$zone,$district,$new_image_name,$new_image_path,$address,$postal_address,$id);
  $stmt_insert->execute();
  $stmt_insert->close();

}



?>