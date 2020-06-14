<?php
include 'dbconn/dbconn.php';
include 'include/AdminValidation.php';

if(isset($_POST['dcID'])){

    $sqlSave=mysqli_prepare($conn, "UPDATE cids SET STATUS=0 WHERE CID=?");

    mysqli_stmt_bind_param($sqlSave,'s',$_POST['dcID']);

    mysqli_stmt_execute($sqlSave);

}
include 'SearchCids.php';
?>