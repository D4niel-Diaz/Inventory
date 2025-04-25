<?php 


include_once '../connection.php';


try{


  if(isset($_REQUEST['file_id'])){

    $id = $con->real_escape_string($_REQUEST['file_id']);


    $sql_delete_file = "DELETE FROM backup WHERE id = '$id'";
    $query_delete_file = $con->query($sql_delete_file) or die ($con->error);



  }




}catch(Exception $e){
  echo $e->getMessage();
}







?>