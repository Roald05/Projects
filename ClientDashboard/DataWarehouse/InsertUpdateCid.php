<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/AdminValidation.php';
$exists=false;
if(isset($_POST['cCode1'])&&isset($_POST['cID'])&&isset($_POST['cSync'])&&isset($_POST['cStartD'])){

    $cidCheck=mysqli_prepare($conn, "SELECT * FROM cids WHERE UPPER(CID)=?");
    mysqli_stmt_bind_param($cidCheck,'s',strtoupper(trim($_POST['cCode1'])));

    if(mysqli_stmt_execute($cidCheck)){
        $rs=mysqli_stmt_get_result($cidCheck);
        if (mysqli_num_rows($rs) > 0){
            $exists=true;
        }else{
            $exists=false;
        }
    }

    $sqlSave=mysqli_prepare($conn, "SELECT * FROM cids WHERE CID=? AND BUSINESSID=? AND STATUS=1");

    mysqli_stmt_bind_param($sqlSave,'ss',$_POST['cID'],$_SESSION['buCode']);

    if(mysqli_stmt_execute($sqlSave)){

        $rs=mysqli_stmt_get_result($sqlSave);

        if(mysqli_num_rows($rs)>0){

            while($result=mysqli_fetch_assoc($rs)){
                $bCode=$result["CID"];
            }

            if($exists==true && (strtoupper(trim($_POST['cCode1'])) != strtoupper(trim($bCode)))){
                echo '0';
            }else{
                $query="UPDATE cids SET CID=?,ALLOW_SYNC=?,START_DATE=? WHERE CID=? AND BUSINESSID=?";

                $sqUpdatetC=mysqli_prepare($conn, $query);

                mysqli_stmt_bind_param($sqUpdatetC,'sssss',$_POST['cCode1'],$_POST['cSync'],$_POST['cStartD'],$bCode,$_SESSION['buCode']);
                mysqli_stmt_execute($sqUpdatetC);
            }

        }else{
            if($exists){
                echo '0';
            }else{
                $sqInsertC=mysqli_prepare($conn, "Insert into cids(CID,ALLOW_SYNC,START_DATE,STATUS,BUSINESSID) VALUES(?,?,?,1,?)");

                mysqli_stmt_bind_param($sqInsertC,'ssss',$_POST['cCode1'],$_POST['cSync'],$_POST['cStartD'],$_SESSION['buCode']);
                mysqli_stmt_execute($sqInsertC);
            }
        }
    }
}
?>