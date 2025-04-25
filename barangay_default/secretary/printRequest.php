
<?php 

include_once '../connection.php';
session_start();

try{

  if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'secretary'){

    
    $sql_barangay_information = "SELECT * FROM `barangay_information`";
    $stmt_barangay_information = $con->prepare($sql_barangay_information) or die ($con->error);
    $stmt_barangay_information->execute();
    $result_barangay_information = $stmt_barangay_information->get_result();
    $row_barangay_information = $result_barangay_information->fetch_assoc();

    
    if($row_barangay_information['image'] != '' || $row_barangay_information['image'] != null){
       $image = '<img src="'.$row_barangay_information['image_path'].'" class="img-circle" id="barangay_logo" alt="logo">';
    }else{
      $image = '<img src="../assets/logo/black.png" class="img-circle" id="barangay_logo" alt="logo">';
    }


    if(isset($_REQUEST['request']) && isset($_REQUEST['purpose'])){
      $resident_id = $con->real_escape_string(($_REQUEST['request']));
      $id = $con->real_escape_string(($_REQUEST['purpose']));


      $sql = "SELECT certificate_request.certificate_type, certificate_request.purpose, residence_information.first_name, residence_information.middle_name, residence_information.last_name,
      residence_information.age, residence_information.civil_status,residence_information.gender
      FROM certificate_request LEFT JOIN residence_information ON certificate_request.residence_id = residence_information.residence_id
      WHERE id = '$id' AND certificate_request.residence_id = '$resident_id'";
      $query = $con->query($sql) or die ($con->error);
      $row = $query->fetch_assoc();

      if($row['gender'] == 'Male'){
        $gender = 'He';
      }else{
        $gender = 'She';
      }

      date_default_timezone_set('Asia/Manila');
      $today = date('jS');   
      $month = date("F");
      $year = date("Y");

      if($row['middle_name'] != ''){
        $middle_name_resident = $row['middle_name'][0].'. ';
      }else{
        $middle_name_resident = '';
      }

      

      $sql_position = "SELECT position_id FROM position WHERE position = 'chairman'";
      $stmt_position = $con->prepare($sql_position) or die ($con->error);
      $stmt_position->execute();
      $result_position = $stmt_position->get_result();
      $row_position = $result_position->fetch_assoc();
      $chairman = $row_position['position_id'];



      $sql_official = "SELECT official_information.first_name, official_information.middle_name, official_information.last_name, official_information.image, official_information.image_path,
      official_status.position FROM official_information INNER JOIN official_status ON   official_information.official_id = official_status.official_id WHERE position = '$chairman'";
      $query_official = $con->query($sql_official) or die ($con->error);
      $row_official = $query_official->fetch_assoc();
      $count_official = $query_official->num_rows;

      if($count_official != 0){
       
        if($row_official['image'] != '' || $row_official['image'] != null){

          $official_image = '<img src="'.$row_official['image_path'].'" class="img-thumbnail bg-white border-0" id="barangay_official" alt="official">';
        }else{
          $official_image = '<img src="../assets/dist/img/image.png" class="img-thumbnail bg-white border-0" id="barangay_official" alt="official">';
        }
        
        if($row_official['middle_name'] != ''){
          $official_middle_name = $row_official['middle_name'][0].'.';
        }else{
          $official_middle_name = ' ';
        }

      }

        


      
    
      

     


    }

   




    
  
  }else{
   echo '<script>
          window.location.href = "../login.php";
        </script>';
  }

}catch(Exception $e){
  echo $e->getMessage();
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>

 

  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">

  <style>
      #barangay_logo{
      height: 200px;
      width:auto;
      max-width:500px;
    }
    #maynila{
      height: 200px;
      width:auto;
      max-width:500px;
    }
    #barangay_official{
      height: 150px;
      width:auto;
      max-width:500px;
    }
    @media print {
    .container {
      
        -webkit-print-color-adjust: exact; 
    }
}

#qwe{
  background-image: url('../assets/logo/seal.png');
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  background-position: center;
  width: 100%;
  height: 100%;

}


  </style>

</head>
<body>


 

<?php 


if($count_official != 0){

  ?>

<div class="container">

<div class="d-flex justify-content-around">
  <div >
    <?= $image ?>
  </div>
  <div class=" text-center" style="font-size:17pt; font-weight: 500">
    <br>
      Republic of the Philippines <br>
      City of Manila <br>

                <?= $row_barangay_information['barangay'].' '. $row_barangay_information['zone'].', '.$row_barangay_information['district'] ?><br>
                  <?= $row_barangay_information['address'];?>
                  <br>
                 OFFICE OF THE BARANGAY CHAIRMAN
  </div>
  <div >
  <img src="../assets/logo/maynila.png" class="img-circle" id="maynila" alt="logo">
  </div>
</div>

<hr style="height: 10px; background-color: skyblue;">
    <br>
    <br>
  
  
  <div class="container text-lg transparentbox " id="qwe">
 
    
    <div class="d-flex justify-content-center"><h1 class="font-weight-bolder" style="font-size: 50px;">CERTIFICATION</h1> </div>
    <span style="font-size:20pt">TO WHOM IT MAY CONCERN:</span>
    <br>
    <br>

    <p class="pl-5 ml-5" style="font-size:20pt; padding: 0; margin: 0;">This is certify that <b><u style="text-transform: uppercase"><?= $row['first_name'] .' '.  $middle_name_resident . $row['last_name'].', '. $row['age']?></u></b> years of age, <?= $row['civil_status'] ?></p>
    <p class="pl-5"  style="font-size:20pt; ">whose signature appears below is a bonafide resident of this Barangay with postal address <b><?= $row_barangay_information['postal_address'] ?></b></p>
 
    <p class="pl-5 ml-5" style="font-size:20pt; padding: 0; margin: 0;">He/She is a person of good moral character and a law-abiding citizen of </p>
    <p class="pl-5"  style="font-size:20pt; "><?= $row_barangay_information['barangay'] ?>, <?= $row_barangay_information['zone'] ?>. As per record, He/She has no derogatroy, no criminal record has been file against him/her in the Barangay as of this date.</p>
    <br>
    <p class="pl-5 ml-5" style="font-size:20pt; padding: 0; margin: 0;">This certification is being issued upon the request of the person </p>
    <p class="pl-5"  style="font-size:20pt; ">mentioned above for <b><?= strtoupper($row['purpose']) ?>.</b></p>

    <p class="pl-5 ml-5" style="font-size:20pt; padding: 0; margin: 0;">Done in the City of Manila this <b><u> <?= '_'.$today .'_' ?> </u> day of <?= $month ?>, <u><?= $year?></u>.<b>  </p>
    <br>
    
    <div class="d-flex justify-content-around">
      <div> 
     
      <br><br>
        <br>
        <br>
        <br>
       
        <hr style="height: 5x; background-color: black; margin: 0; padding:0;">
        <p style="margin: 0; padding:0;">
      
     
      SIGNATURE OVER PRINTED NAME</p>
    </div>
     <div> <p></p></div>
     
      <div class="text-center">
      <?= $official_image ?>
      <p><?= $row_official['first_name'] .' '. $official_middle_name . $row_official['last_name'] ?> LPT<br>Punong Barangay(KPBS)</p>
      </div>
    
    </div>

    <div class="text-center">
      <p class="mb-0">Barangay Officials</p>
      <p class="p-0 m-0" style="font-weight: 700"> 
          <?php
          $i = 0;
          $sql_official_display = "SELECT official_status.position, position.position as official_position, official_information.first_name, official_information.middle_name, official_information.last_name FROM official_status
          INNER JOIN position ON official_status.position = position.position_id 
          INNER JOIN official_information ON official_status.official_id = official_information.official_id ORDER BY position.position ASC";
                $query_official_display = $con->query($sql_official_display) or die ($con->error);
          while($row_official_display = $query_official_display->fetch_assoc()){ 
            
            if($row_official_display['middle_name'] != ''){
              $official_middle_name_display = $row_official_display['middle_name'][0].'.';
            }else{
              $official_middle_name_display = ' ';
            }
            
            ?>
            
                  <?= ucfirst($row_official_display['official_position']) .'. '. $row_official_display['first_name'].' ' . $official_middle_name_display .' '. $row_official_display['last_name'] ?>,
          
              <?php
              $i++;
              if($i % 3 == 0) {
                  echo '<br />';
              }
          }
          ?>
          </p>
          <p class="m-0 p-0" style="font-size: 15px"> VALID WITH SIGNATURE OF PUNONG BARANGAY ONLY.</p>
          <p class="m-0 p-0" style="font-size: 10px"><?= $row_barangay_information['barangay'] ?> ASENSO GARANTISADO</p>
          <p class="m-0 p-0" style="font-size: 10px">Note: Not Valid Without Barangay Dry Seal</p>
  
    </div>
 
  </div>


</div>





  <?php

}else{

  echo '<h1 style="font-size: 150px">NO CHAIRMAN OR OFFICAL</h1>';

}


?>













 
 
 
<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.js"></script>

<script>
$(document).ready(function(){
  
   
    var printContents = $("body").html();
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
     setTimeout(function(){ 
             window.close();
  }, 5000);
  
})
</script>


</body>
</html>


              