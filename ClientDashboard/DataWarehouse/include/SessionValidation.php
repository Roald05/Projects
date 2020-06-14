<?php
if(!isset($_COOKIE['KUNDERID'])){
    header("Location: SetCookie.php");
}else{
    $exp_kontrollSql=mysqli_prepare($conn, "SELECT EXPIRE_DATE,STATUS FROM devices WHERE KUNDERID=?");

    mysqli_stmt_bind_param($exp_kontrollSql,'s',$_COOKIE['KUNDERID']);
    if(mysqli_stmt_execute($exp_kontrollSql)){
        $rs=mysqli_stmt_get_result($exp_kontrollSql);
        while($result=mysqli_fetch_assoc($rs)){

            if(date('Y-m-d',strtotime($result["EXPIRE_DATE"])) < date('Y-m-d')){
                header("Location: ExpKontrollMessage.php");
            }else{
                if($result["STATUS"]==0){
                    header("Location: ExpKontrollMessage.php");
                }else{
                    if(!isset($_SESSION['perdoruesi']) || !isset($_SESSION['fjalekalimi'])){
                        header("Location: index.php");
                    }
                }
            }
        }
    }
}
?>