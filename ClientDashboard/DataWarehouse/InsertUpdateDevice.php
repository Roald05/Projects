<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/AdminValidation.php';

if(isset($_POST['dCode1'])&&isset($_POST['expDate'])&&isset($_POST['dDesc'])&&isset($_POST['valD'])&&isset($_POST['DID'])){

    $sqlSave=mysqli_prepare($conn, "SELECT * FROM devices WHERE ID=? AND BUSINESSID=? AND STATUS=1");

    mysqli_stmt_bind_param($sqlSave,'ss',$_POST['DID'],$_SESSION['buCode']);

    if(mysqli_stmt_execute($sqlSave)){

        $rs=mysqli_stmt_get_result($sqlSave);

        if(mysqli_num_rows($rs)>0){

            while($result=mysqli_fetch_assoc($rs)){
                $bCode=$result["ID"];
            }

            $query="UPDATE devices SET KUNDERID=?,EXPIRE_DATE=?,DESCRIPTION=?,VALIDATION_DATE=? WHERE ID=? AND BUSINESSID=?";

            $sqUpdatetD=mysqli_prepare($conn, $query);

            mysqli_stmt_bind_param($sqUpdatetD,'ssssss',$_POST['dCode1'],$_POST['expDate'],$_POST['dDesc'],$_POST['valD'],$bCode,$_SESSION['buCode']);
            mysqli_stmt_execute($sqUpdatetD);

        }else{
            $sqInsertD=mysqli_prepare($conn, "Insert into devices(KUNDERID,INSERT_DATE,VALIDATION_DATE,EXPIRE_DATE,DESCRIPTION,STATUS,BUSINESSID) VALUES(?,?,0,?,?,1,?)");

            mysqli_stmt_bind_param($sqInsertD,'sssss',$_POST['dCode1'],date('Y-m-d H:i'),$_POST['expDate'],$_POST['dDesc'],$_SESSION['buCode']);
            mysqli_stmt_execute($sqInsertD);
        }
    }
}

include 'SearchDevices.php';
?>