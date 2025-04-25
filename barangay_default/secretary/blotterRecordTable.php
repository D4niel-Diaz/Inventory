<?php 


include_once '../connection.php';

try{





  $sql_blooter_check = "SELECT * FROM blotter_record ";
  if(isset($_REQUEST['search']['value'])){
    $sql_blooter_check .= " WHERE status LIKE '%" . $_REQUEST['search']['value'] . "%' ";
    $sql_blooter_check .= " OR blotter_id LIKE '%" . $_REQUEST['search']['value'] . "%' ";
    $sql_blooter_check .= " OR remarks LIKE '%" . $_REQUEST['search']['value'] . "%' ";
    $sql_blooter_check .= " OR  type_of_incident LIKE '%" . $_REQUEST['search']['value'] . "%' ";
    $sql_blooter_check .= " OR  location_incident LIKE '%" . $_REQUEST['search']['value'] . "%' ";
    $sql_blooter_check .= " OR  date_incident LIKE '%" . $_REQUEST['search']['value'] . "%' ";
    $sql_blooter_check .= " OR  date_reported LIKE '%" . $_REQUEST['search']['value'] . "%' ";
  }

  $query_blotter_check = $con->prepare($sql_blooter_check) or die ($con->error);
  $query_blotter_check->execute();
  $result_blotter_check = $query_blotter_check->get_result(); 
  $totalData = $result_blotter_check->num_rows;


  if(isset($_REQUEST['order'])){
    $sql_blooter_check .= ' ORDER BY '.
    $_REQUEST['order']['0']['column'].
    ' '.
    $_REQUEST['order']['0']['dir'].
    ' ';
  }else{
    $sql_blooter_check .= ' ORDER BY date_reported DESC ';
  }


  if($_REQUEST['length'] != -1){
    $sql_blooter_check .= ' LIMIT '.
    $_REQUEST['start'].
    ' ,'.
    $_REQUEST['length'].
    ' ';
  }

  $query_blotter_check = $con->prepare($sql_blooter_check) or die ($con->error);
  $query_blotter_check->execute();
  $result_blotter_check = $query_blotter_check->get_result(); 
  $data = [];
  while($row_blotter_check = $result_blotter_check->fetch_assoc()){

    date_default_timezone_set('Asia/Manila');
    $date_incident= date("m/d/Y - h:i A", strtotime($row_blotter_check['date_incident']));

   
    $date_reported= date("m/d/Y - h:i A", strtotime($row_blotter_check['date_reported']));


    if($row_blotter_check['status'] == 'NEW'){
      $status_blotter = '<span class="badge badge-primary">'.$row_blotter_check['status'] .'</span>';
    }else{
      $status_blotter = '<span class="badge badge-warning">'.$row_blotter_check['status'] .'</span>';
    }

    if($row_blotter_check['remarks'] == 'CLOSED'){
      $remarks_blotter = '<span class="badge badge-success">'.$row_blotter_check['remarks'] .'</span>';
    }else{
      $remarks_blotter = '<span class="badge badge-danger">'.$row_blotter_check['remarks'] .'</span>';
    }

    $subdata = [];
    $subdata[] = '<input type="checkbox" id="'. $row_blotter_check['blotter_id'].'" class="sub_checkbox">';
    $subdata[] = $row_blotter_check['blotter_id'];
    $subdata[] = $status_blotter;
    $subdata[] = $remarks_blotter;
    $subdata[] = $row_blotter_check['type_of_incident'];
    $subdata[] = $row_blotter_check['location_incident'];
    $subdata[] = $date_incident;
    $subdata[] = $date_reported;
    $subdata[] =   '<i style="cursor: pointer;  color: yellow;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-book-open text-lg px-2 viewRecords" id="'.$row_blotter_check['blotter_id'].'"></i>';

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