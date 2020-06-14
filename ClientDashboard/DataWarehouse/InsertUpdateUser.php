<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/AdminValidation.php';
$exists=false;
if(isset($_POST['uCode1'])&&isset($_POST['uPasw'])&&isset($_POST['UID'])&&$_POST['uTipi']){

    $userCheck=mysqli_prepare($conn, "SELECT * FROM users WHERE UPPER(USERNAMED)=?");
    mysqli_stmt_bind_param($userCheck,'s',strtoupper(trim($_POST['uCode1'])));

    if(mysqli_stmt_execute($userCheck)){
        $rs=mysqli_stmt_get_result($userCheck);
        if (mysqli_num_rows($rs) > 0){
            $exists=true;
        }else{
            $exists=false;
        }
    }

    $sqlSave=mysqli_prepare($conn, "SELECT * FROM users WHERE USERID=? AND BUSINESSID=? AND STATUS=1 ");

    mysqli_stmt_bind_param($sqlSave,'ss',$_POST['UID'],$_SESSION['buCode']);

    if(mysqli_stmt_execute($sqlSave)){

        $rs=mysqli_stmt_get_result($sqlSave);

        if(mysqli_num_rows($rs)>0){

            while($result=mysqli_fetch_assoc($rs)){
                $bCode=$result["USERID"];
                $uUsernamed=$result["USERNAMED"];
            }

            if($exists==true && strtoupper(trim($_POST['uCode1'])) != strtoupper(trim($uUsernamed))){
                echo '0';
            }else{
                $query="UPDATE users SET USERNAMED=? ,FJALEKALIM=?, TIPI=? WHERE USERID=? AND BUSINESSID=?";

                $sqUpdatetU=mysqli_prepare($conn, $query);

                mysqli_stmt_bind_param($sqUpdatetU,'sssss',$_POST['uCode1'],$_POST['uPasw'],$_POST['uTipi'],$bCode,$_SESSION['buCode']);
                mysqli_stmt_execute($sqUpdatetU);
            }
        }else{

            if($exists==true){
                echo '0';
            }else{
                $sqInsertU=mysqli_prepare($conn, "Insert into users(USERNAMED,FJALEKALIM,STATUS,BUSINESSID,TIPI) VALUES(?,?,1,?,?)");

                mysqli_stmt_bind_param($sqInsertU,'ssss',$_POST['uCode1'],$_POST['uPasw'],$_SESSION['buCode'],$_POST['uTipi']);
                mysqli_stmt_execute($sqInsertU);
            }

        }
    }
}
?>