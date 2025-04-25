<?php 


include_once '../connection.php';

try{


  $col = ['id','message','date'];
 
  
  $sql = "SELECT * FROM activity_log";
  


  if(isset($_REQUEST['search']['value'])){
    $sql .= " WHERE message LIKE '%" .$_REQUEST['search']['value']. "%' ";
    $sql .= " OR date LIKE '%" .$_REQUEST['search']['value']. "%' ";
  }
  $stmt = $con->prepare($sql) or die ($con->error);
  $stmt->execute();
  $result = $stmt->get_result();
  $totalData = $result->num_rows;
  

  if(isset($_REQUEST['order'])){
    $sql .= ' ORDER BY '.
    $col[$_REQUEST['order']['0']['column']].
    ' '.
    $_REQUEST['order']['0']['dir'].
    ' ';
  }else{
    $sql .= ' ORDER BY id DESC ';
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
    $subdata = [];
    $subdata[] = $row['id'];
    $subdata[] = $row['message'];
    $subdata[] = $row['date'];
    $data[] = $subdata;
  }

  $json_data = [
    'draw' => intval($_REQUEST['draw']),
    'recordsTotal' => intval($totalData),
    'recordsFiltered' => intval($totalData),
    'data' => $data,
  ];

  echo json_encode($json_data);



}catch(Exception $e){
  echo $e->getMessage();
}










?>