
<?php 

include_once '../connection.php';
session_start();

try{


  
  if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'){
  
    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
    $stmt_user = $con->prepare($sql_user) or die ($con->error);
    $stmt_user->bind_param('s',$user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];
  
  
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

 
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/css/sweetalert2.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/phone code/intlTelInput.min.css">
  
 <style>
    #image_residence{
      height: 120px;
      width:auto;
      max-width:500px;
    }
    .iti__country-list {
      background-color: #343a40;
    }
    .iti { width: 100%; }
 </style>
</head>
<body class="hold-transition dark-mode sidebar-mini layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble " src="../assets/dist/img/loader.gif" alt="AdminLTELogo" height="70" width="70">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <h5><a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></h5>
      </li>
      <li class="nav-item d-none d-sm-inline-block" style="font-variant: small-caps;">
        <h5 class="nav-link text-white" ><?= $barangay ?></h5>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <h5 class="nav-link text-white" >-</h5>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <h5 class="nav-link text-white" ><?= $zone ?></h5>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <h5 class="nav-link text-white" >-</h5>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <h5 class="nav-link text-white" ><?= $district ?></h5>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="myProfile.php" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <?php 
                if($user_image != '' || $user_image != null || !empty($user_image)){
                  echo '<img src="../assets/dist/img/'.$user_image.'" class="img-size-50 mr-3 img-circle alt="User Image">';
                }else{
                  echo '<img src="../assets/dist/img/image.png" class="img-size-50 mr-3 img-circle alt="User Image">';
                }
              ?>
            
              <div class="media-body">
                <h3 class="dropdown-item-title py-3">
                  <?= ucfirst($first_name_user) .' '. ucfirst($last_name_user) ?>
                </h3>
              </div>
            </div>
            <!-- Message End -->
          </a>         
          <div class="dropdown-divider"></div>
          <a href="../logout.php" class="dropdown-item dropdown-footer">LOGOUT</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
    <?php 
        if($image != '' || $image != null || !empty($image)){
          echo '<img src="'.$image_path.'" id="logo_image" class="img-circle elevation-5 img-bordered-sm" alt="logo" style="width: 70%;">';
        }else{
          echo ' <img src="../assets/logo/logo.png" id="logo_image" class="img-circle elevation-5 img-bordered-sm" alt="logo" style="width: 70%;">';
        }

      ?>
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
    

    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../assets/dist/img/logo.png" class="img-circle elevation-5 img-bordered-sm" alt="User Image">
        </div>
        <div class="info text-center">
          <a href="#" class="d-block text-bold"><?= strtoupper($user_type) ?></a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
              Barangay Official
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="newOfficial.php" class="nav-link ">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>New Official</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="allOfficial.php" class="nav-link">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>List of Official</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="officialEndTerm.php" class="nav-link ">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>Official End Term</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link bg-indigo ">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Residence
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="newResidence.php" class="nav-link active">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>New Residence</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="allResidence.php" class="nav-link">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>All Residence</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="archiveResidence.php" class="nav-link ">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>Archive Residence</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="requestCertificate.php" class="nav-link">
              <i class="nav-icon fas fa-certificate"></i>
              <p>
                Certificate
              </p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="usersResident.php" class="nav-link ">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>Resident</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="userAdministrator.php" class="nav-link">
                  <i class="fas fa-circle nav-icon text-red"></i>
                  <p>Administrator</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="position.php" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Position
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="blotterRecord.php" class="nav-link">
              <i class="nav-icon fas fa-clipboard"></i>
              <p>
                Blotter Record
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report.php" class="nav-link">
              <i class="nav-icon fas fa-bookmark"></i>
              <p>
                Reports
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="settings.php" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="systemLog.php" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>
                System Logs
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="backupRestore.php" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Backup/Restore
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
   

    <!-- Main content -->
    <section class="content mt-3">
      <div class="container-fluid">

        <form id="newResidenceForm" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="row mb-3">
          <div class="col-sm-4">
            <div class="card card-indigo card-outline h-100">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-thumbnail" src="../assets/dist/img/blank_image.png" alt="User profile picture" style="cursor: pointer;" id="image_residence">
                  <input type="file" name="add_image" id="add_image" style="display: none;">
                </div>

                <h3 class="profile-username text-center "><span id="keyup_first_name"></span> <span id="keyup_last_name"></span></h3>
  
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Voters</label>
                      <select name="add_voters" id="add_voters" class="form-control">
                      <option value=""></option>
                        <option value="NO">NO</option>
                        <option value="YES">YES</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group ">
                      <label >Gender</label>
                      <select name="add_gender" id="add_gender" class="form-control">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group ">
                      <label >Date of Birth</label>
                      <input type="date" class="form-control" id="add_birth_date" name="add_birth_date">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group ">
                      <label >Place of Birth</label>
                      <input type="text" class="form-control" id="add_birth_place" name="add_birth_place">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group ">
                      <label >PWD</label>
                      <select name="add_pwd" id="add_pwd" class="form-control">
                      <option value=""></option>
                        <option value="NO">NO</option>
                        <option value="YES">YES</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12" id="pwd_check" style="display: none;">
                    <div class="form-group ">
                      <label >TYPE OF PWD</label>
                        <input type="text" class="form-control" id="add_pwd_info" name="add_pwd_info">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group ">
                      <label >Single Parent</label>
                      <select name="add_single_parent" id="add_single_parent" class="form-control">
                        <option value=""></option>
                        <option value="NO">NO</option>
                        <option value="YES">YES</option>
                      </select>
                    </div>
                  </div>
                </div>



               
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <div class="col-sm-8">
            <div class="card card-indigo card-tabs h-100">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="basic-info-tab" data-toggle="pill" href="#basic-info" role="tab" aria-controls="basic-info" aria-selected="true">Basic Info</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="other-info-tab" data-toggle="pill" href="#other-info" role="tab" aria-controls="other-info" aria-selected="false">Other Info</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="guardian-tab" data-toggle="pill" href="#guardian" role="tab" aria-controls="guardian" aria-selected="false">Guardian</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade active show" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
                      <p class="lead text-center">Personal Details</p>
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group ">
                            <label>First Name </label>
                            <input type="text" class="form-control" id="add_first_name" name="add_first_name" >
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group ">
                            <label>Middle Name</label>
                            <input type="text" class="form-control" id="add_middle_name" name="add_middle_name" >
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group ">
                            <label>Last Name </label>
                            <input type="text" class="form-control" id="add_last_name" name="add_last_name" >
                          </div>  
                        </div>
                      </div>
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group ">
                              <label >Suffix</label>
                              <input type="text" class="form-control" id="add_suffix" name="add_suffix" >
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group ">
                              <label >Civil Status</label>
                              <select name="add_civil_status" id="add_civil_status" class="form-control">
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                              </select>
                            </div>
                          </div>
                          
                          <div class="col-sm-6">
                            <div class="form-group ">
                              <label >Religion</label>
                              <input type="text" class="form-control" id="add_religion" name="add_religion">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group ">
                              <label >Nationality</label>
                              <input type="text" class="form-control" id="add_nationality" name="add_nationality">
                            </div>
                          </div>                              
                        </div>
                  </div>
                  <div class="tab-pane fade" id="other-info" role="tabpanel" aria-labelledby="other-info-tab">
                        <p class="lead text-center">Address</p>
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Municipality</label>
                              <input type="text" class="form-control" id="add_municipality" name="add_municipality">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Zip</label>
                              <input type="text" class="form-control" id="add_zip" name="add_zip" >
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Barangay</label>
                              <input type="text" class="form-control" id="add_barangay" name="add_barangay" >
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>House Number</label>
                              <input type="text" class="form-control" id="add_house_number" name="add_house_number" >
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                            <label>Street</label>
                            <input type="text" class="form-control" id="add_street" name="add_street" >
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Address</label>
                              <input type="text" class="form-control" id="add_address" name="add_address" >
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label >Contact Number</label>
                              <input type="text" class="form-control" maxlength="11" id="add_contact_number" name="add_contact_number" width="100%">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label>Email Address</label>
                              <input type="text" class="form-control" id="add_email_address" name="add_email_address" >
                            </div>
                          </div>
                        </div>
                  </div>
                  <div class="tab-pane fade" id="guardian" role="tabpanel" aria-labelledby="guardian-tab">
                   
                      <p class="lead text-center">Guardian</p>
                      <div class="row">

                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Father's Name</label>
                            <input type="text" class="form-control" id="add_fathers_name" name="add_fathers_name" >
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Mother's Name</label>
                            <input type="text" class="form-control" id="add_mothers_name" name="add_mothers_name" >
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Guardian</label>
                            <input type="text" class="form-control" id="add_guardian" name="add_guardian" >
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>Guardian Contact</label>
                            <input type="text" class="form-control" maxlength="11" id="add_guardian_contact" name="add_guardian_contact" >
                          </div>
                        </div>

                      </div>
                    
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit"  class="btn btn-success px-4 elevation-3"> <i class="fas fa-user-plus"></i> ADD NEW RESIDENT</button>
              </div> 
              <!-- /.card -->
            </div>

          </div>
        </div>
        
        </form>
            


      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y"); ?> - <?php echo date('Y', strtotime('+1 year'));  ?> </strong>
 
    <div class="float-right d-none d-sm-inline-block">
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.js"></script>
<script src="../assets/plugins/popper/umd/popper.min.js"></script>
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/plugins/jszip/jszip.min.js"></script>
<script src="../assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>
<script src="../assets/plugins/select2/js/select2.full.min.js"></script>
<script src="../assets/plugins/moment/moment.min.js"></script>
<script src="../assets/plugins/chart.js/Chart.min.js"></script>
<script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../assets/plugins/phone code/intlTelInput.js"></script>

<script>
  $(document).ready(function(){

    $("#add_pwd").change(function(){
      var pwd_check = $(this).val();

      if(pwd_check == 'YES'){
        $("#pwd_check").css('display', 'block');
        $("#add_pwd_info").prop('disabled', false);
      }else{
        $("#pwd_check").css('display', 'none');
        $("#add_pwd_info").prop('disabled', true);
      }

    })


    $(function () {
        $.validator.setDefaults({
          submitHandler: function (form) {
            $.ajax({
              url: 'addNewResidence.php',
              type: 'POST',
              data: new FormData(form),
              processData: false,
              contentType: false,
              success:function(data){
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  type: 'success',
                  html: '<b>Added Residence has Successfully<b>',
                  width: '400px',
                  confirmButtonColor: '#6610f2',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  timer: 2000,
                }).then(()=>{
                  window.location.reload();
                })
              }
            }).fail(function(){
              Swal.fire({
                title: '<strong class="text-danger">Ooppss..</strong>',
                type: 'error',
                html: '<b>Something went wrong with ajax !<b>',
                width: '400px',
                confirmButtonColor: '#6610f2',
              })
            })
          }
        });
      $('#newResidenceForm').validate({
        ignore: "",
        rules: {
          add_first_name: {
            required: true,
            minlength: 2
          },
          add_last_name: {
            required: true,
            minlength: 2
          },
        
          add_birth_date: {
            required: true,
          },
          add_contact_number:{
            required: true,
            minlength: 11
          },
          add_address:{
            required: true,
          },
          add_voters:{
            required: true,
          },
          add_pwd:{
            required: true,
          },
          add_single_parent:{
            required: true,
          },
          add_pwd_info:{
            required: true,
          },
          add_email_address:{
            email: true,
          },

        },
        
        messages: {
          add_first_name: {
            required: "Please provide a First Name",
            minlength: "First Name must be at least 2 characters long"
          },
          add_last_name: {
            required: "Please provide a Last Name",
            minlength: "Last Name must be at least 2 characters long"
          },
        
          add_birth_date: {
            required: "Please provide a Birth Date",
          },
          add_address: {
            required: "Please provide a Address",
          },
          add_contact_number: {
            required: "Please provide a Contact NUmber",
            minlength: "Input Exact Contact Number"
          },
          add_email_address:{
            email:"Enter Valid Email!",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
          element.closest('.form-group-sm').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    })
   

    $("#add_first_name, #add_last_name").keyup(function(){
      var add_first_name = $("#add_first_name").val();
      var add_last_name = $("#add_last_name").val();
      $("#keyup_first_name").text(add_first_name);
      $("#keyup_last_name").text(add_last_name);
    })

    $("#image_residence").click(function(){
          $("#add_image").click();
      });

    function displayImge(input){
      if(input.files && input.files[0]){
        var reader = new FileReader();
        var add_image = $("#add_image").val().split('.').pop().toLowerCase();

        if(add_image != ''){
          if(jQuery.inArray(add_image,['gif','png','jpg','jpeg']) == -1){
            Swal.fire({
              title: '<strong class="text-danger">ERROR</strong>',
              type: 'error',
              html: '<b>Invalid Image File<b>',
              width: '400px',
              confirmButtonColor: '#6610f2',
            })
            $("#add_image").val('');
            $("#image_residence").attr('src', '../assets/dist/img/blank_image.png');
            return false;
          }
        }

        reader.onload = function(e){
          $("#image_residence").attr('src',e.target.result);
          $("#image_residence").hide();
          $("#image_residence").fadeIn(650);
        }

        reader.readAsDataURL(input.files[0]);

      }
    }  

    $("#add_image").change(function(){
      displayImge(this);
    })
   


    
  })
</script>
<script>
// Restricts input for each element in the set of matched elements to the given inputFilter.
(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));

 
  $("#add_contact_number,#add_zip, #add_guardian_contact, #add_age").inputFilter(function(value) {
  return /^-?\d*$/.test(value); 
  
  });


  $("#add_first_name, #add_middle_name, #add_last_name, #add_suffix, #add_religion, #add_nationality, #add_municipality, #add_fathers_name, #add_mothers_name, #add_guardian").inputFilter(function(value) {
  return /^[a-z, ]*$/i.test(value); 
  });
  
  $("#add_street, #add_birth_place, #add_house_number").inputFilter(function(value) {
  return /^[0-9a-z, ,-]*$/i.test(value); 
  });

</script>

</body>
</html>
