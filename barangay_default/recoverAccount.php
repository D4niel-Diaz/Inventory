<?php 


include_once 'connection.php';

try{

  
  $username = $con->real_escape_string($_POST['username']);

  $sql = "SELECT contact_number FROM `users` WHERE (username = ? OR id = ?)";
  $stmt = $con->prepare($sql) or die ($con->error);
  $stmt->bind_param('ss',$username,$username);
  $stmt->execute();
  $result = $stmt->get_result();

  $count= $result->num_rows;

  




}catch(Exception $e){
  echo $e->getMessage();
}










?>




<!-- Modal -->
<div class="modal fade" id="recoverModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <form id="recoverPasswordForm" method="post">
      <div class="modal-body">
        <div class="container-fluid">
          <?php 
          
          
          if($count > 0){
            $row = $result->fetch_array();
     
            if(strlen((string)$row['contact_number']) == 11){
              $myNumber = $row['contact_number'][0] . $row['contact_number'][1] . $row['contact_number'][2] . $row['contact_number'][3] . $row['contact_number'][4] . $row['contact_number'][5] . $row['contact_number'][6] .'XXXX';

            }else{
              $myNumber = $row['contact_number'][0] . $row['contact_number'][1] . $row['contact_number'][2] . $row['contact_number'][3] . $row['contact_number'][4] . $row['contact_number'][5] . 'XXXX';
            }
         
          
            ?>

          <div class="row">
            <input type="hidden" name="check_username" id="check_username" value="<?= $username?>">
            <input type="hidden" name="check_number" id="check_number" value="<?= $row['contact_number'];?>">
            <div class="col-sm-12">
              <div class="form-group">
                <h3>YOUR NUMBER - <?= $myNumber ?></h3>
                <input type="text" name="contact_number" maxlength="4" id="contact_number" class="form-control" placeholder="CORRECT LAST 4 DGIT NUMBER">
              </div>
            </div>
            <div class="col-sm-12 ">
              <div  class="form-group">
                <div class="input-group mb-3" id="show_hide_password">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent"><i class="fas fa-key"></i></span>
                  </div>
                  <input type="password"  id="new_password" name="new_password" class="form-control" placeholder="NEW PASSWORD"  style="border-right: none;" >
                  <div class="input-group-append bg">
                    <span class="input-group-text bg-transparent"> <a href="" style=" text-decoration:none;"><i class="fas fa-eye-slash" aria-hidden="true"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 ">
              <div  class="form-group">
                <div class="input-group mb-3" id="show_hide_password_confirm">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent"><i class="fas fa-key"></i></span>
                  </div>
                  <input type="password"  id="new_confirm_password" name="new_confirm_password" class="form-control" placeholder="CONFIRM PASSWORD"  style="border-right: none;" >
                  <div class="input-group-append bg">
                    <span class="input-group-text bg-transparent"> <a href="" style=" text-decoration:none;"><i class="fas fa-eye-slash" aria-hidden="true"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

            <?php
          }else{
           echo  '<h5 class="text-center">WRONG USERNAME OR RESIDENT NUMBER</h5>';
           
          }
          
          
          
          ?>
         
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-black elevation-5 px-3 btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> CLOSE</button>
        <?php      
          if($count > 0){
            echo '<button type="submit" class="btn btn-primary btn-flat  elevation-5 px-3"><i class="fas fa-save"></i> SAVE</button>';
          }
          ?>
        
      </div>
      </form>


    </div>
  </div>
</div>


<script>


$(document).ready(function(){




  $(function () {
        $.validator.setDefaults({
          submitHandler: function (form) {
            Swal.fire({
              title: '<strong class="text-warning">Are you sure?</strong>',
              html: "<b>You want save this password?</b>",
              type: 'info',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, save it!',
              allowOutsideClick: false,
              width: '400px',
            }).then((result) => {
              if (result.value) {
                
             

                  
                      $.ajax({
                          url: 'recorverNewPassword..php',
                          type: 'POST',
                          data: $(form).serialize(),
                          cache: false,
                          success:function(data){
    
                              if(data == 'error'){
                                Swal.fire({
                                    title: '<strong class="text-danger">ERROR</strong>',
                                    type: 'error',
                                    html: '<b>4 DIGIT NOT MATCH<b>',
                                    width: '400px',
                                    confirmButtonColor: '#6610f2',
                                  })
                              }else if(data == 'error1'){
                                Swal.fire({
                                    title: '<strong class="text-danger">ERROR</strong>',
                                    type: 'error',
                                    html: '<b>PASSWORD NOT MATCH<b>',
                                    width: '400px',
                                    confirmButtonColor: '#6610f2',
                                  })
                              }else{

                                Swal.fire({
                                  title: '<strong class="text-success">SUCCESS</strong>',
                                  type: 'success',
                                  html: '<b>Updated Password has Successfully<b>',
                                  width: '400px',
                                  confirmButtonColor: '#6610f2',
                                  allowOutsideClick: false,
                                  showConfirmButton: false,
                                  timer: 2000,
                                }).then(()=>{
                                  window.location.href="login.php";
                                })

                              }


                           
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
            })
            
          }
        });
      $('#recoverPasswordForm').validate({
        rules: {
         
          new_password: {
            required: true,
           minlength: 8
          },
          contact_number: {
            required: true,
           minlength: 4
          },
          new_confirm_password: {
            required: true,
           minlength: 8
          },
        
        },
        messages: {
         
          new_password: {
            required: "New Password is Required",
            minlength: "New Password must be at least 8 characters long"
           
          },

          new_confirm_password: {
            required: "Confrim Password is Required",
            minlength: "Confrim Password must be at least 8 characters long"
          },
        
        },
      
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },
      });
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
    $("#show_hide_password_confirm a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password_confirm input').attr("type") == "text"){
            $('#show_hide_password_confirm input').attr('type', 'password');
            $('#show_hide_password_confirm i').addClass( "fa-eye-slash" );
            $('#show_hide_password_confirm i').removeClass( "fa-eye" );
        }else if($('#show_hide_password_confirm input').attr("type") == "password"){
            $('#show_hide_password_confirm input').attr('type', 'text');
            $('#show_hide_password_confirm i').removeClass( "fa-eye-slash" );
            $('#show_hide_password_confirm i').addClass( "fa-eye" );
        }
    });

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

 
  $("#contact_number").inputFilter(function(value) {
  return /^-?\d*$/.test(value); 
  
  });



</script>