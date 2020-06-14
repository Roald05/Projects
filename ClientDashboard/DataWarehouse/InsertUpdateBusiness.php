<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/AdminValidation.php';
$exists=false;

if(isset($_POST['bCode1'])&&isset($_POST['bDesc1'])&&isset($_POST['businessID'])){

    $businessCheck=mysqli_prepare($conn, "SELECT * FROM businesses WHERE UPPER(CODE)=?");
    mysqli_stmt_bind_param($businessCheck,'s',strtoupper(trim($_POST['bCode1'])));

    if(mysqli_stmt_execute($businessCheck)){
        $rs=mysqli_stmt_get_result($businessCheck);
        if (mysqli_num_rows($rs) > 0){
            $exists=true;
        }else{
            $exists=false;
        }
    }

    $sqlSave=mysqli_prepare($conn, "SELECT * FROM businesses WHERE BUSINESSID=?");

    mysqli_stmt_bind_param($sqlSave,'s',$_POST['businessID']);

    if(mysqli_stmt_execute($sqlSave)){

        $rs=mysqli_stmt_get_result($sqlSave);

        if(mysqli_num_rows($rs)>0){

            while($result=mysqli_fetch_assoc($rs)){
                $bCode=$result["BUSINESSID"];
                $bName=$result["CODE"];
            }

            if($exists==true && strtoupper(trim($bName))!=strtoupper(trim($_POST['bCode1']))){
                echo '0';
            }else{
                $query="UPDATE businesses SET CODE=? ,DESCRIPTION=?,UPDATE_DATE=? WHERE BUSINESSID=?";

                $sqUpdatetBu=mysqli_prepare($conn, $query);

                mysqli_stmt_bind_param($sqUpdatetBu,'ssss',$_POST['bCode1'],$_POST['bDesc1'],date('Y-m-d'),$bCode);
                mysqli_stmt_execute($sqUpdatetBu);
            }

        }else{

            if($exists){
                echo '0';
            }else{
                $sqInsertBu=mysqli_prepare($conn, "Insert into businesses(CODE,DESCRIPTION,STATUS,INSERT_DATE) VALUES(?,?,1,?)");

                mysqli_stmt_bind_param($sqInsertBu,'sss',$_POST['bCode1'],$_POST['bDesc1'],date('Y-m-d'));
                mysqli_stmt_execute($sqInsertBu);
            }
        }
    }
}
?>