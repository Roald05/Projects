<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/AdminValidation.php';

if(isset($_POST['dbuID'])){

    $sqlSave=mysqli_prepare($conn, "UPDATE businesses SET STATUS=0 WHERE BUSINESSID=?");

    mysqli_stmt_bind_param($sqlSave,'s',$_POST['dbuID']);

    mysqli_stmt_execute($sqlSave);




    $sqlSelectDevices=mysqli_prepare($conn, "SELECT ID FROM devices WHERE BUSINESSID=? AND STATUS=1");

    mysqli_stmt_bind_param($sqlSelectDevices,'s',$_POST['dbuID']);
    if(mysqli_stmt_execute($sqlSelectDevices)){
        $rs=mysqli_stmt_get_result($sqlSelectDevices);
        while($result=mysqli_fetch_assoc($rs)){

            $sqlDevice=mysqli_prepare($conn, "UPDATE devices SET KUNDERID=? WHERE ID=?");

            mysqli_stmt_bind_param($sqlDevice,'ss',bin2hex(openssl_random_pseudo_bytes(8)),$result['ID']);

            mysqli_stmt_execute($sqlDevice);

        }
    }
}
include 'SearchBusinesses.php';
?>