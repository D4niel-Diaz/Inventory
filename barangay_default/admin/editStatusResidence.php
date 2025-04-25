<?php 

include_once '../connection.php';

try{

  if(isset($_REQUEST['status_residence']) && isset($_REQUEST['data_status'])){

    $status_residence_id = $con->real_escape_string(trim($_REQUEST['status_residence']));

    
    $sql_check_status = "SELECT status FROM residence_status WHERE residence_id = ?";
    $stmt_check_status = $con->prepare($sql_check_status) or die ($con->error);
    $stmt_check_status->bind_param('s',$status_residence_id);
    $stmt_check_status->execute();
    $result_check_status = $stmt_check_status->get_result();
    $row_check_status = $result_check_status->fetch_assoc();

    if($row_check_status['status'] == 'ACTIVE'){
      $data_status = 'INACTIVE';
      
    }else{
      $data_status = 'ACTIVE';
     
    }
    
    $sql_update_status = "UPDATE residence_status SET `status` = ? WHERE residence_id = ?";
    $stmt_update_status = $con->prepare($sql_update_status) or die ($con->error);
    $stmt_update_status->bind_param('ss',$data_status,$status_residence_id);
    $stmt_update_status->execute();
    $stmt_update_status->close();
  

  }

}catch(Exception $e){
  echo $e->getMessage();
}



?>