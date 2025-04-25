<?php 


include_once '../connection.php';


try{

  $user_id = $con->real_escape_string($_REQUEST['user_id']);
  $date_request = $con->real_escape_string($_REQUEST['date_request']);
  $date_issued = $con->real_escape_string($_REQUEST['date_issued']);
  $date_expired = $con->real_escape_string($_REQUEST['date_expired']);
  $status = $con->real_escape_string($_REQUEST['status']);


  $whereClause = [];
  if(!empty($date_request)){
    $whereClause[] = "certificate_request.date_request='$date_request'";
  }

  if(!empty($date_issued)){
    $whereClause[] = "certificate_request.date_issued='$date_issued'";
  }

  if(!empty($date_expired)){
    $whereClause[] = "certificate_request.date_expired='$date_expired'";
  }

  if(!empty($status)){
    $whereClause[] = "certificate_request.status='$status'";
  }

  $where = '';

  if(count($whereClause) > 0){
    $where .= ' AND '.implode(' AND ',$whereClause);
  }



  
  $sql_user_request = "SELECT * FROM certificate_request WHERE residence_id = '$user_id'".$where;
  if(isset($_REQUEST['search']['value'])){
    $sql_user_request .= "AND (purpose LIKE '%" . $_REQUEST['search']['value']. "%' ";
    $sql_user_request .= "OR purpose LIKE '%" . $_REQUEST['search']['value']. "%' )";
  }

  $query = $con->query($sql_user_request) or die ($con->error);
  $totalData = $query->num_rows;

  if(isset($_REQUEST['order'])){
    $sql_user_request .= ' ORDER BY '.
    $_REQUEST['order']['0']['column'].
    ' '.
    $_REQUEST['order']['0']['dir'].
    ' ';
  }else{
    $sql_user_request .= ' ORDER BY a_i DESC ';
  }

  if($_REQUEST['length'] != -1){
    $sql_user_request .= ' LIMIT '.
    $_REQUEST['start'].
    ' ,'.
    $_REQUEST['length'].
    ' ';
  }

  $query = $con->query($sql_user_request) or die ($con->error);
  $data = [];

  while($row_request = $query->fetch_array()){
    $date_today = date("Y-m-d");  


    if($row_request['status'] == 'PENDING'){
      $status = '<span class="badge badge-warning">'.$row_request['status'].'</span>';
      $tools = '<i  style="cursor: pointer;  color: yellow;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fas fa-eye text-lg px-2 acceptStatus" id="'.$row_request['residence_id'].'" data-id="'.$row_request['id'].'" data-toggle="tooltip" data-placement="left" title="View Request"></i> 
               ';
    }elseif($row_request['status'] == 'ACCEPTED'){
      
      $status = '<span class="badge badge-success">'.$row_request['status'].'</span>';

      if($row_request['date_expired'] <= $date_today){
        $tools = '  <i  style="cursor: pointer;  color: red;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fas fa-times-circle text-lg px-2 acceptStatus" id="'.$row_request['residence_id'].'" data-id="'.$row_request['id'].'" data-toggle="tooltip" data-placement="left" title="Expired"></i>';
      }else{
        $tools = '<a href="printRequest.php?request='.$row_request['residence_id'].'&purpose='.$row_request['id'].'" target="_blank"  style="cursor: pointer;  color: pink;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fas fa-print text-lg px-2 printRequest"  data-toggle="tooltip" data-placement="left" title="Print"> </a>
        <i  style="cursor: pointer;  color: lime;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fas fa-check text-lg px-2 acceptStatus" id="'.$row_request['residence_id'].'" data-id="'.$row_request['id'].'" data-toggle="tooltip" data-placement="left" title="View Record"></i>';
      }

     
    }else{
      $status = '<span class="badge badge-danger">'.$row_request['status'].'</span>';
      $tools = ' <i  style="cursor: pointer;  color: red;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fas fa-times text-lg px-2 acceptStatus" id="'.$row_request['residence_id'].'" data-id="'.$row_request['id'].'" data-toggle="tooltip" data-placement="left" title="View Record"></i>';
    }

    if($row_request['date_issued'] != ''){
      $date_issued = date("m/d/Y", strtotime($row_request['date_issued']));
    }else{
      $date_issued = '';
    }

    if($row_request['date_expired'] != ''){
      $date_expired = date("m/d/Y", strtotime($row_request['date_expired']));
    }else{
      $date_expired= '';
    }


    $subdata = [];

    $subdata[] = $row_request['purpose'];
    $subdata[] = $row_request['date_request'];
    $subdata[] = $date_issued ;
    $subdata[] = $date_expired;
    $subdata[] = $status;
    $subdata[] = $tools;

    $data[]= $subdata;
  }



  $json_data = [
    'draw' => intval($_REQUEST['draw']),
    'recordsTotal' =>intval($totalData),
    'recordsFiltered' =>intval($totalData),
    'data' => $data,
    'total' => number_format($totalData),
  ];

  echo json_encode($json_data);


}catch(Exception $e){
  echo $e->getMessage();
}




?>