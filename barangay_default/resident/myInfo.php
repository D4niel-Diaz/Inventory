<?php 

include_once '../connection.php';
session_start();

try {
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'resident') {
        $user_id = $_SESSION['user_id'];
        
        // Prepare statement for user data
        $sql_user = "SELECT * FROM `users` WHERE `id` = ?";
        $stmt_user = $con->prepare($sql_user) or die ($con->error);
        $stmt_user->bind_param('s', $user_id);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        
        if($result_user->num_rows === 0) {
            throw new Exception("User not found");
        }
        
        $row_user = $result_user->fetch_assoc();
        $first_name_user = htmlspecialchars($row_user['first_name']);
        $last_name_user = htmlspecialchars($row_user['last_name']);
        $user_type = htmlspecialchars($row_user['user_type']);
        $user_image = htmlspecialchars($row_user['image']);

        // Prepare statement for resident data
        $sql_resident = "SELECT residence_information.*, residence_status.* FROM residence_information
                        INNER JOIN residence_status ON residence_information.residence_id = residence_status.residence_id
                        WHERE residence_information.residence_id = ?";
        $stmt_resident = $con->prepare($sql_resident) or die ($con->error);
        $stmt_resident->bind_param('s', $user_id);
        $stmt_resident->execute();
        $result_resident = $stmt_resident->get_result();
        
        if($result_resident->num_rows === 0) {
            throw new Exception("Resident information not found");
        }
        
        $row_resident = $result_resident->fetch_assoc();

        // Prepare statement for barangay information
        $sql = "SELECT * FROM `barangay_information`";
        $query = $con->prepare($sql) or die ($con->error);
        $query->execute();
        $result = $query->get_result();
        
        if($result->num_rows === 0) {
            throw new Exception("Barangay information not found");
        }
        
        $row = $result->fetch_assoc();
        $barangay = htmlspecialchars($row['barangay']);
        $zone = htmlspecialchars($row['zone']);
        $district = htmlspecialchars($row['district']);
        $image = htmlspecialchars($row['image']);
        $image_path = htmlspecialchars($row['image_path']);
        $id = htmlspecialchars($row['id']);
        $postal_address = htmlspecialchars($row['postal_address']);

    } else {
        header("Location: ../login.php");
        exit();
    }
} catch(Exception $e) {
    echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '"); window.location.href = "../login.php";</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>

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
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <style>
    .rightBar:hover {
      border-bottom: 3px solid red;
    }
    
    #barangay_logo {
      height: 150px;
      width: auto;
      max-width: 500px;
    }

    .logo {
      height: 150px;
      width: auto;
      max-width: 500px;
    }
    
    .wrapper {
      background-image: url('../assets/logo/cover.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      width: 100%;
      height: auto;
      animation-name: example;
      animation-duration: 5s;
    }

    @keyframes example {
      from {opacity: 0;}
      to {opacity: 1;}
    }

    .dark-mode .custom-control-label::before, 
    .dark-mode .custom-file-label, 
    .dark-mode .custom-file-label::after, 
    .dark-mode .custom-select, 
    .dark-mode .form-control:not(.form-control-navbar):not(.form-control-sidebar), 
    .dark-mode .input-group-text {
      background-color: transparent;
      color: #fff;
    }

    .editInfo {
      background-color: rgba(0, 0, 0, 0);
      color: #fff;
      border: none;
      outline: none;
      width: 100%;
    }
    
    .editInfo:focus {
      background-color: rgba(0, 0, 0, 0);
      color: #fff;
      border: none;
      outline: none;
      width: 100%;
    }
    
    #edit_gender, #edit_civil_status, #edit_voters, #edit_pwd, select {
      -moz-appearance: none;
      -webkit-appearance: none;
      border: none;
      width: 100%;
      background-color: transparent;
      color: #fff;
    }
    
    #edit_gender, #edit_civil_status, #edit_voters, #edit_pwd, #edit_single_parent, option:focus {
      outline: none;
      border: none;
      box-shadow: none;
      background-color: transparent;
      color: #fff;
    }

    /* For IE10 */
    #edit_gender, #edit_civil_status, #edit_voters, #edit_pwd, #edit_single_parent select::-ms-expand {
      display: none;
      background-color: transparent;
      color: #fff;
    }
    
    select option {
      background: #343a40;
      color: #fff;
      text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
    }
    
    #display_edit_image_residence {
      height: 120px;
      width: auto;
      max-width: 500px;
      cursor: pointer;
    }
    
    .widget-user-image {
      position: relative;
    }
    
    .widget-user-image:hover::after {
      content: "Change Photo";
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0,0,0,0.7);
      color: white;
      text-align: center;
      padding: 5px;
    }
  </style>
</head>
<body class="layout-top-nav dark-mode">

<div class="wrapper p-0 maring-0 bg-transparent">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md" style="background-color: #0037af">
    <div class="container">
      <a href="#" class="navbar-brand">
        <img src="../assets/dist/img/<?= $image ?>" alt="logo" class="brand-image img-circle">
        <span class="brand-text text-white" style="font-weight: 700"><?= $barangay ?> <?= $zone ?>, <?= $district ?></span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item">
          <a href="dashboard.php" class="nav-link text-white rightBar"><i class="fas fa-home"></i> DASHBOARD</a>
        </li>
        <li class="nav-item">
          <a href="profile.php" class="nav-link text-white rightBar" style="text-transform:uppercase;"><i class="fas fa-user-alt"></i> <?= $last_name_user ?>-<?= $user_id ?></a>
        </li>
        <li class="nav-item">
          <a href="../logout.php" class="nav-link text-white rightBar" style="text-transform:uppercase;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: transparent">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid pt-5">
        <form id="editResidenceForm" method="post" enctype="multipart/form-data">
          <div class="card card-widget widget-user" style="border: 10px solid rgba(0,54,175,.75); border-radius: 0;">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-dark pl-5">
              <h3 class="widget-user-username"><br></h3>
              <h5 class="widget-user-desc">RESIDENT NO. <?= htmlspecialchars($row_resident['residence_id']) ?></h5>
            </div>
            <div class="widget-user-image">
              <?php 
                if(!empty($row_resident['image_path'])) {
                  echo '<img src="'.htmlspecialchars($row_resident['image_path']).'" class="img-circle elevation-2" alt="User Image" id="display_edit_image_residence">';
                } else {
                  echo '<img src="../assets/dist/img/blank_image.png" class="img-circle elevation-2" alt="User Image" id="display_edit_image_residence">';
                }
              ?>
              <input type="file" name="edit_image_residence" id="edit_image_residence" style="display: none;" accept="image/*">
            </div>
            <div class="card-footer mt-4">
              <div class="table-responsive">
                <input type="hidden" name="edit_residence_id" value="<?= htmlspecialchars($row_resident['residence_id']) ?>">
                <table style="font-size:11pt;" class="table table-bordered">
                  <tbody>
                    <tr>
                      <td colspan="3">
                        <div class="d-flex justify-content-between">
                          <div> FIRST NAME<br>
                            <input type="text" class="editInfo form-control form-control-sm" value="<?= htmlspecialchars($row_resident['first_name']) ?>" id="edit_first_name" name="edit_first_name" size="30" required> 
                            <input type="hidden" value="false" id="edit_first_name_check"> 
                          </div>
                          <div>MIDDLE NAME<br>
                            <input type="text" class="editInfo form-control form-control-sm" value="<?= htmlspecialchars($row_resident['middle_name']) ?>" id="edit_middle_name" name="edit_middle_name" size="20"> 
                            <input type="hidden" id="edit_middle_name_check" value="false">
                          </div>
                          <div>      
                            LAST NAME<br>
                            <input type="text" class="editInfo form-control form-control-sm" value="<?= htmlspecialchars($row_resident['last_name']) ?>" id="edit_last_name" name="edit_last_name" size="20" required> 
                            <input type="hidden" value="false" id="edit_last_name_check">
                          </div>
                          <div>      
                            SUFFIX<br>
                            <input type="text" class="editInfo form-control form-control-sm" value="<?= htmlspecialchars($row_resident['suffix']) ?>" id="edit_suffix" name="edit_suffix" size="5">  
                            <input type="hidden" id="edit_suffix_check" value="false">
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        DATE OF BIRTH<br>
                        <input type="date" class="editInfo form-control form-control-sm" value="<?= htmlspecialchars(strftime('%Y-%m-%d', strtotime($row_resident['birth_date']))) ?>" name="edit_birth_date" id="edit_birth_date" required/>
                        <input type="hidden" id="edit_birth_date_check" value='false'>
                      </td>
                      
                    </tr>
                    <tr>
                      
                      <td>
                        GENDER<br>
                        <select name="edit_gender" id="edit_gender" class="form-control">
                          <option value="Male" <?= $row_resident['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                          <option value="Female" <?= $row_resident['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                        <input type="hidden" id="edit_gender_check" value="false">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        EMAIL ADDRESS<br>
                        <input type="email" class="editInfo form-control form-control-sm" value="<?= htmlspecialchars($row_resident['email_address']) ?>" name="edit_email_address" id="edit_email_address">
                        <input type="hidden" id="edit_email_address_check" value="false">
                      </td>
                      <td colspan="2">
                        CONTACT NUMBER<br>
                        <input type="text" maxlength="11" class="editInfo form-control form-control-sm" value="<?= htmlspecialchars($row_resident['contact_number']) ?>" name="edit_contact_number" id="edit_contact_number" required>
                        <input type="hidden" id="edit_contact_number_check" value="false">
                      </td>         
                    </tr>
                  </tbody>
                </table>
              </div>
              <button type="submit" class="btn btn-success elevation-5 px-3"><i class="fas fa-edit"></i> UPDATE</button>
            </div>
          </div>
        </form>  
      </div><!--/. container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer text-white" style="background-color: #0037af">
    <div class="float-right d-none d-sm-block"></div>
    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($postal_address) ?> 
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
<script src="../assets/plugins/jquery-validation/jquery-validate.bootstrap-tooltip.min.js"></script>

<script>
  $(document).ready(function(){
    // Image upload functionality
    $('#display_edit_image_residence').on('click', function(){
      $("#edit_image_residence").click();
    });
    
    $("#edit_image_residence").change(function(){
      editDisplayImage(this);
    });

    function editDisplayImage(input) {
      if(input.files && input.files[0]) {
        var reader = new FileReader();
        var edit_image_residence = $("#edit_image_residence").val().split('.').pop().toLowerCase();
        
        if(edit_image_residence != '') {
          if($.inArray(edit_image_residence, ['gif','png','jpeg','jpg']) == -1) {
            Swal.fire({
              title: '<strong class="text-danger">ERROR</strong>',
              type: 'error',
              html: '<b>Invalid Image File<b>',
              width: '400px',
              confirmButtonColor: '#6610f2',
            });
            $("#edit_image_residence").val('');
            $("#display_edit_image_residence").attr('src', '<?= htmlspecialchars($row_resident['image_path']) ?>');
            return false;
          }
        }
        
        reader.onload = function(e) {
          $("#display_edit_image_residence").attr('src', e.target.result);
          $("#display_edit_image_residence").hide();
          $("#display_edit_image_residence").fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    // Form validation and submission
    $.validator.setDefaults({
      submitHandler: function(form) {
        Swal.fire({
          title: '<strong class="text-warning">Are you sure?</strong>',
          html: "<b>You want to edit your details?</b>",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Edit it!',
          allowOutsideClick: false,
          width: '400px',
        }).then((result) => {
          if(result.value) {
            var formData = new FormData(form);
            
            // Add all the check values to formData
            $('[id$="_check"]').each(function() {
              formData.append(this.id, $(this).val());
            });
            
            $.ajax({
              url: 'editResidence.php',
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              cache: false,
              success: function(data) {
                Swal.fire({
                  title: '<strong class="text-success">SUCCESS</strong>',
                  type: 'success',
                  html: '<b>Your details have been updated successfully<b>',
                  width: '400px',
                  confirmButtonColor: '#6610f2',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  timer: 2000,
                }).then(() => {
                  window.location.reload();
                });
              }
            }).fail(function() {
              Swal.fire({
                title: '<strong class="text-danger">Ooppss..</strong>',
                type: 'error',
                html: '<b>Something went wrong with the request!<b>',
                width: '400px',
                confirmButtonColor: '#6610f2',
              });
            });
          }
        });
      }
    });
    
    $('#editResidenceForm').validate({
      rules: {
        edit_first_name: {
          required: true,
          minlength: 2
        },
        edit_last_name: {
          required: true,
          minlength: 2
        },
        edit_birth_date: {
          required: true,
        },
        edit_contact_number: {
          required: true,
          minlength: 11,
          digits: true
        },
        edit_email_address: {
          email: true,
        },
      },
      messages: {
        edit_first_name: {
          required: "<span class='text-danger text-bold'>First Name is Required</span>",
          minlength: "<span class='text-danger'>First Name must be at least 2 characters long</span>"
        },
        edit_last_name: {
          required: "<span class='text-danger text-bold'>Last Name is Required</span>",
          minlength: "<span class='text-danger'>Last Name must be at least 2 characters long</span>"
        },
        edit_birth_date: {
          required: "<span class='text-danger text-bold'>Birth Date is Required</span>",
        },
        edit_contact_number: {
          required: "<span class='text-danger text-bold'>Contact Number is Required</span>",
          minlength: "<span class='text-danger'>Input exact 11-digit contact number</span>",
          digits: "<span class='text-danger'>Only numbers are allowed</span>"
        },
        edit_email_address: {
          email: "<span class='text-danger text-bold'>Enter a valid email address!</span>",
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('div').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    // Track changes for all fields
    $('input, select').on('change', function() {
      var fieldId = $(this).attr('id');
      var checkFieldId = fieldId + '_check';
      var originalValue = $(this).data('original-value') || $(this).val();
      
      if($(this).val() !== originalValue) {
        $('#' + checkFieldId).val('true');
      } else {
        $('#' + checkFieldId).val('false');
      }
    });

    // Store original values on page load
    $('input, select').each(function() {
      $(this).data('original-value', $(this).val());
    });

    // Input filtering
    $.fn.inputFilter = function(inputFilter) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
        if(inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if(this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      });
    };

    $("#edit_contact_number, #edit_zip, #edit_guardian_contact, #edit_age").inputFilter(function(value) {
      return /^-?\d*$/.test(value); 
    });

    $("#edit_first_name, #edit_middle_name, #edit_last_name, #edit_suffix").inputFilter(function(value) {
      return /^[a-z ,.'-]*$/i.test(value); 
    });
  });
</script>
</body>
</html>