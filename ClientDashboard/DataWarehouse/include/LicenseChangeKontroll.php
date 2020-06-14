<?php
include 'dbconn/dbconn.php';


$exp_kontrollSql=mysqli_prepare($conn, "SELECT KUNDERID FROM devices WHERE KUNDERID=? AND STATUS=1");

mysqli_stmt_bind_param($exp_kontrollSql,'s',$_COOKIE['KUNDERID']);
if(mysqli_stmt_execute($exp_kontrollSql)){

    $rs=mysqli_stmt_get_result($exp_kontrollSql);

    if(mysqli_num_rows($rs)>0){

        while($result=mysqli_fetch_assoc($rs)){
            if($_COOKIE['KUNDERID']!=$result["KUNDERID"]){
                header("Location: SetCookie.php");
                unset($_SESSION['buID']);
                unset($_SESSION['businessCode']);
                unset($_SESSION['uStatus']);
                unset($_SESSION['buCode']);
                unset($_SESSION['buDesc']);
                unset($_SESSION['perdoruesi']);
                unset($_SESSION['fjalekalimi']);
                unset($_SESSION['CID']);
                unset($_SESSION['date1']);
                unset($_SESSION['date2']);
                session_destroy();
            }
        }

    }else{
        header("Location: SetCookie.php");
        unset($_SESSION['buID']);
        unset($_SESSION['businessCode']);
        unset($_SESSION['uStatus']);
        unset($_SESSION['buCode']);
        unset($_SESSION['buDesc']);
        unset($_SESSION['perdoruesi']);
        unset($_SESSION['fjalekalimi']);
        unset($_SESSION['CID']);
        unset($_SESSION['date1']);
        unset($_SESSION['date2']);
        session_destroy();
    }
}
?>