<?php 


include_once '../connection.php';


try{

  $first_name = $con->real_escape_string($_REQUEST['first_name']);
  $middle_name = $con->real_escape_string($_REQUEST['middle_name']);
  $last_name = $con->real_escape_string($_REQUEST['last_name']);
  $resident_id = $con->real_escape_string($_REQUEST['resident_id']);

  $whereClause = [];

  if(!empty($resident_id))

  $whereClause[] = "residence_information.residence_id='$resident_id'";


  if(!empty($first_name))

    $whereClause[] = "residence_information.first_name LIKE '%" .$first_name. "%' ";

  if(!empty($middle_name))

    $whereClause[] = "residence_information.middle_name LIKE '%" .$middle_name. "%' ";

  if(!empty($last_name))

    $whereClause[] = "residence_information.last_name LIKE '%" .$last_name. "%' ";

   

  $where = '';

  if(count($whereClause) > 0)
    $where .= ' AND ' . implode(' AND ',$whereClause);
 

  $sql = "SELECT residence_information.residence_id, 
  residence_information.first_name, 
  residence_information.middle_name, 
  residence_information.last_name, 
  residence_information.image,
  residence_information.image_path, 
  users.username, users.password
  FROM residence_information INNER JOIN users ON residence_information.residence_id = users.id
  WHERE user_type != 'admin' AND user_type != 'secretary'
  " .$where;
  $stmt = $con->prepare($sql) or die ($con->error);
  $stmt->execute();
  $stmt->store_result();
  $totalData = $stmt->num_rows;
  $totalFiltered = $totalData;



  $stmt = $con->prepare($sql) or die ($con->error);
  $stmt->execute();
  $stmt->store_result();
  $totalData = $stmt->num_rows;

  if(isset($_REQUEST['order'])){
    $sql .= ' ORDER BY '.
    $_REQUEST['order']['0']['column'].
    ' '.
    $_REQUEST['order']['0']['dir'].
    ' ';
  }else{
    $sql .= ' ORDER BY last_name DESC ';
  }

  if($_REQUEST['length'] != -1){
    $sql .= ' LIMIT '.
    $_REQUEST['start'].
    ' ,'.
    $_REQUEST['length'].
    ' ';
  }


$stmt = $con->prepare($sql) or die ($con->error);
$stmt->execute();
$result = $stmt->get_result();
$data = [];
while($row = $result->fetch_assoc()){
  if($row['image'] != '' || $row['image'] != null || !empty($row['image'])){
    $image = '<span style="cursor: pointer;" class="pop"><img src="'.$row['image_path'].'" alt="residence_image" class="img-circle" width="40"></span>';
  }else{
    $image = '<span style="cursor: pointer;" class="pop"><img src="../assets/dist/img/blank_image.png" alt="residence_image" class="img-circle"  width="40"></span>';
  }

  if($row['middle_name'] != ''){
    $middle_name = ucfirst($row['middle_name'])[0].'.';
  }else{
    $middle_name = '';
  }

  $subdata = [];
  $subdata[] = $image;
  $subdata[] = $row['residence_id'];
  $subdata[] =  ucfirst($row['first_name']).' '. $middle_name .' '. ucfirst($row['last_name']); 
  $subdata[] = $row['username'];
  $subdata[] = $row['password'];
  $subdata[] = '<i style="cursor: pointer;  color: yellow;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-user-edit text-lg px-3 viewUserResidence" id="'.$row['residence_id'].'"></i>
';
  $data[] = $subdata;
}

$json_data = [
  'draw' => intval($_REQUEST['draw']),
  'recordsTotal' => intval($totalData),
  'recordsFiltered' => intval($totalFiltered),
  'data' => $data,
];

echo json_encode($json_data);

}catch(Exception $e){
  echo $e->getMessage();
}



?>