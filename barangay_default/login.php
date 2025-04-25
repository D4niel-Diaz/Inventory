
<?php 
include_once 'connection.php';
session_start();

try{

  if(isset($_SESSION['user_id']) && $_SESSION['user_type']){


    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $query = $con->query($sql) or die ($con->error);
    $row = $query->fetch_assoc();
    $account_type = $row['user_type'];
    if ($account_type == 'admin') {
    echo '<script>
            window.location.href="admin/dashboard.php";
        </script>';
    
    } elseif ($account_type == 'secretary') {
        echo '<script>
            window.location.href="secretary/dashboard.php";
        </script>';
    
    } else {
        echo '<script>
        window.location.href="resident/dashboard.php";
    </script>';
    
}





}

$sql = "SELECT * FROM `barangay_information`";
  $query = $con->prepare($sql) or die ($con->error);
  $query->execute();
  $result = $query->get_result();
  while($row = $result->fetch_assoc()){
      $barangay = $row['barangay'];
      $zone = $row['zone'];
      $district = $row['district'];
      $image = $row['image'];
      $image_path = $row['image_path'];
      $id = $row['id'];
      $postal_address = $row['postal_address'];
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
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/plugins/sweetalert2/css/sweetalert2.min.css">
 

  <style>
    .rightBar:hover{
      border-bottom: 3px solid red;
     
    }
    


    
    #barangay_logo{
      height: 150px;
      width:auto;
      max-width:500px;
    }

    .logo{
      height: 150px;
      width:auto;
      max-width:500px;
    }
    .content-wrapper{
      background-image: url('assets/logo/cover.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      width: 100%;
        height: 100%;
        animation-name: example;
        animation-duration: 5s;
       
       
    }


@keyframes example {
  from {opacity: 0;}
  to {opacity: 1.5;}
}





  </style>


</head>
<body  class="hold-transition layout-top-nav">


<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md " style="background-color: #0037af">
    <div class="container">
      <a href="" class="navbar-brand">
        <img src="assets/dist/img/<?= $image  ?>" alt="logo" class="brand-image img-circle " >
        <span class="brand-text  text-white" style="font-weight: 700">BARANGAY PORTAL</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->


       
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto " >
          <li class="nav-item">
            <a href="index.php" class="nav-link text-white rightBar" >HOME</a>
          </li>
          <li class="nav-item">
            <a href="register.php" class="nav-link text-white rightBar"><i class="fas fa-user-plus"></i> REGISTER</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white rightBar" style="  border-bottom: 3px solid red;"><i class="fas fa-user-alt"></i> LOGIN</a>
          </li>
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
 
    
  
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-4" >
      <div class="container-fluid pt-5 "  style="background-color: rgba(0,54,175,.75);">
      <br>
      <br>
        <div class="row justify-content-center">
          <form id="loginForm" method="post">
          <div class="card " style="border: 10px solid rgba(0,54,175,.75); border-radius: 0;">
            <div class="card-body text-center text-white">
              <div class="col-sm-12">
                <img src="assets/dist/img/<?= $image;?>" alt="logo" class="img-circle logo">
              </div>
              <div class="col-sm-12">
                <h1 class="card-text" style="font-weight: 1000; color: #0036af">BARANGAY PORTAL</h1>
              </div>
             
              <div class="col-sm-12 mt-4">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-transparent"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" id="username" name="username" class="form-control" placeholder="USERNAME OR RESIDENT NUMBER" >
                  </div>
                </div>
              </div>
              <div class="col-sm-12 mt-4">
                <div  class="form-group">
                  <div class="input-group mb-3" id="show_hide_password">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-transparent"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password"  id="password" name="password" class="form-control" placeholder="PASSWORD"  style="border-right: none;">
                    <div class="input-group-append bg">
                      <span class="input-group-text bg-transparent"> <a href="" style=" text-decoration:none;"><i class="fas fa-eye-slash" aria-hidden="true"></i></a></span>
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-sm-12 text-right">
                    <a href="forgot.php">Forgot Password</a>
            </div>
            <div class="col-sm-12 mt-4">
                <button type="submit" class="btn btn-flat bg-blue btn-lg btn-block">Sign In</button>
            </div>
          </div>
          </form>
        </div>

  
      

      </div>


      <br>
        <br>
        <br>
        
       
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 

 


</div>
<!-- ./wrapper -->
<footer class="main-footer text-white" style="background-color: #0037af">
    <div class="float-right d-none d-sm-block">
    
    </div>
  <i class="fas fa-map-marker-alt"></i> <?= $postal_address ?> 
  </footer>




<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.js"></script>
<script src="assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

<script>
  $(document).ready(function() {


    $("#loginForm").submit(function(e){
      e.preventDefault();
      var username = $("#username").val();
      var password = $("#password").val();
      if(username == '' || password == ''){
        Swal.fire({
          title: '<strong class="text-danger">WARNING</strong>',
          type: 'warning',
          html: '<b>Username and Password is Required<b>',
          width: '400px',
        })
      }else{
        $.ajax({
          url: 'loginForm.php',
          type: 'POST',
          data: $(this).serialize(),
          success:function(data){
              if(data == 'errorUsername'){
                Swal.fire({
                  title: '<strong class="text-danger">ERROR</strong>',
                  type: 'error',
                  html: '<b>Incorrect Username or Password<b>',
                  width: '400px',
                })
              }else if(data =='errorPassword'){
                Swal.fire({
                  title: '<strong class="text-danger">ERROR</strong>',
                  type: 'error',
                  html: '<b>Incorrect Username or Password<b>',
                  width: '400px',
                })
              }else if(data == 'admin'){
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  type: 'success',
                  html: '<b>Login Successfully<b>',
                  width: '400px',
                  showConfirmButton:  false,
                  allowOutsideClick: false,
                  timer: 2000
                }).then(()=>{
                  window.location.href = 'admin/dashboard.php';
                })
              }else if(data == 'secretary'){
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  type: 'success',
                  html: '<b>Login Successfully<b>',
                  width: '400px',
                  showConfirmButton:  false,
                  allowOutsideClick: false,
                  timer: 2000
                }).then(()=>{
                  window.location.href = 'secretary/dashboard.php';
                })
              }else if(data == 'resident'){
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  type: 'success',
                  html: '<b>Login Successfully<b>',
                  width: '400px',
                  showConfirmButton:  false,
                  allowOutsideClick: false,
                  timer: 2000
                }).then(()=>{
                  window.location.href = 'resident/dashboard.php';
                })
              }
          }
        })
      }
    })




    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
});
</script>


</body>
</html>
