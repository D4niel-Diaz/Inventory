<?php 

include_once '../connection.php';


try{




  
  $sql = "SELECT * FROM users WHERE user_type != 'resident' AND user_type !='admin'";
  if($_REQUEST['search']['value']){
    $sql .= " AND (first_name LIKE '%" .$_REQUEST['search']['value']. "%' ";
    $sql .= " OR last_name LIKE '%" .$_REQUEST['search']['value']. "%' ";
    $sql .= " OR username LIKE '%" .$_REQUEST['search']['value']. "%' ";
    $sql .= " OR password LIKE '%" .$_REQUEST['search']['value']. "%' )";
  }

  $query = $con->query($sql) or die ($con->error);
  $totalData = $query->num_rows;


  if(isset($_REQUEST['order'])){
    $sql .= ' ORDER BY '.
    $_REQUEST['order']['0']['column'].
    ' '.
    $_REQUEST['order']['0']['dir'].
    ' ';
  }else{
    $sql .= ' ORDER BY username DESC ';
  }


  if($_REQUEST['length'] != -1){
    $sql .= ' LIMIT '.
    $_REQUEST['start'].
    ' ,'.
    $_REQUEST['length'].
    ' ';
  }

  $query = $con->query($sql) or die ($con->error);
  $data = [];

  while($row = $query->fetch_assoc()){

    if($row['image'] != '' || $row['image'] != null || !empty($row['image'])){
      $image = '<span style="cursor: pointer;" class="pop"><img src="'.$row['image_path'].'" alt="residence_image" class="img-circle" width="40"></span>';
    }else{
      $image = '<span style="cursor: pointer;" class="pop"><img src="../assets/dist/img/image.png" alt="residence_image" class="img-circle"  width="40"></span>';
    }

    if($row['middle_name'] != ''){
      $middle_name = $row['middle_name'][0].'.' .' ';
    }else{
      $middle_name = '';
    }

    
    
    $subdata = [];
    $subdata[] = $image;
    $subdata[] = $row['first_name'] .' '. $middle_name . $row['last_name'];
    $subdata[] = $row['username'];
    $subdata[] = $row['password'];
    $subdata[] = '<i style="cursor: pointer;  color: yellow;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-user-edit text-lg px-3 viewUserAdministrator" id="'.$row['id'].'"></i>
    <i style="cursor: pointer;  color: red;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-times text-lg px-3 deleteUserAdministrator" id="'.$row['id'].'"></i>';
    $data[] = $subdata;
    
  }

  
$json_data = [
  'draw' => intval($_REQUEST['draw']),
  'recordsTotal' => intval($totalData),
  'recordsFiltered' => intval($totalData),
  'data' => $data,
  'total' => number_format($totalData),
];

echo json_encode($json_data);


}catch(Exception $e){
  echo $e->getMessage();
}




?>