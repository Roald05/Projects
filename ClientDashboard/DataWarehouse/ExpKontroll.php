<?php
include 'dbconn/dbconn.php';
include 'include/LicenseChangeKontroll.php';
include 'include/AdminValidation.php';

if(!isset($_COOKIE['KUNDERID'])){
    header("Location: SetCookie.php");
}else{

    $exp_kontrollSql=mysqli_prepare($conn, "SELECT EXPIRE_DATE FROM devices WHERE KUNDERID=? AND STATUS=1");

    mysqli_stmt_bind_param($exp_kontrollSql,'s',$_COOKIE['KUNDERID']);
    if(mysqli_stmt_execute($exp_kontrollSql)){
        $rs=mysqli_stmt_get_result($exp_kontrollSql);
        while($result=mysqli_fetch_assoc($rs)){
            if(date('Y-m-d H:i',strtotime($result["EXPIRE_DATE"])) < date('Y-m-d H:i')){
                header("Location: ExpKontrollMessage.php");
            }
        }
    }
}

?>