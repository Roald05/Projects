<?php
include 'dbconn/dbconn.php';
include 'include/AdminValidation.php';

if(isset($_POST['duID'])){

    $sqDeleteUser=mysqli_prepare($conn, "UPDATE users SET STATUS=0 WHERE USERID=?");

    mysqli_stmt_bind_param($sqDeleteUser,'s',$_POST['duID']);

    mysqli_stmt_execute($sqDeleteUser);

}
include 'SearchUsers.php';
?>