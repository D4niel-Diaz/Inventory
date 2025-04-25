<?php 

include_once 'connection.php';
session_start();


if($_SESSION['user_type'] == 'admin'){
    $user_type_log = 'ADMIN';
}elseif($_SESSION['user_type'] == 'secretary'){
    $user_type_log = 'OFFICIAL';
}else{
    $user_type_log = 'RESIDENT';
}

$sql_user = "SELECT first_name, last_name FROM users WHERE id = ?";
$stmt_user = $con->prepare($sql_user) or die ($con->error);
$stmt_user->bind_param('s',$_SESSION['user_id']);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$row_user = $result_user->fetch_assoc();
$first_name = $row_user['first_name'];
$last_name = $row_user['last_name'];
$status_activity_log = 'logout';


$date_activity = $now = date("j-n-Y g:i A"); 
$message =  $user_type_log. ': '.$first_name.' '. $last_name .' | '. 'LOGOUT';
$sql_system_logs= "INSERT INTO activity_log (`message`, `date`,`status`) VALUES (?,?,?)";
$query_system_logs = $con->prepare($sql_system_logs) or die ($con->error);
$query_system_logs->bind_param('sss',$message,$date_activity,$status_activity_log);
$query_system_logs->execute();
$query_system_logs->close();



unset($_SESSION['user_id']);
unset($_SESSION['user_type']);
session_unset();
session_destroy();

echo '<script>
            window.location.href="login.php";
        </script>';
?>