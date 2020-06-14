<?php
include 'dbconn/dbconn.php';
include 'include/AdminValidation.php';

if(isset($_POST['duID'])){

    $sqlSave=mysqli_prepare($conn, "UPDATE devices SET STATUS=0 WHERE ID=?");

    mysqli_stmt_bind_param($sqlSave,'s',$_POST['duID']);

    mysqli_stmt_execute($sqlSave);

}
include 'SearchDevices.php';
?>