<?php
include "dbconn/dbconn.php";
if
( $_SERVER["REQUEST_METHOD"] == "GET" )
{
    $actualCID="";
    $actualBID="";
    $aSync="";
    if(isset($_GET['BUSINESSID'])&&isset($_GET['CID'])){
        $CID=$_GET['CID'];
        $BUSINESSID=$_GET['BUSINESSID'];


        $sqlCheck=mysqli_prepare($conn, "SELECT c.CID AS CID,c.ALLOW_SYNC AS ALLOW_SYNC,c.START_DATE AS START_DATE ,b.CODE AS BUSINESSID FROM cids c INNER JOIN businesses b ON c.BUSINESSID=b.BUSINESSID WHERE c.CID=? AND b.CODE=? AND c.STATUS=1 AND b.STATUS=1");

        mysqli_stmt_bind_param($sqlCheck,'ss',$CID,$BUSINESSID);

        if(mysqli_stmt_execute($sqlCheck)){
            $rs=mysqli_stmt_get_result($sqlCheck);
            if(mysqli_num_rows($rs)>0){
                while($result=mysqli_fetch_assoc($rs)){
                    $actualCID=$result["CID"];
                    $actualBID=$result["BUSINESSID"];
                    $aSync=(boolval($result["ALLOW_SYNC"])? true : false);
                    $startDate=$result["START_DATE"];
                }
            }
        }
        if($BUSINESSID===$actualBID){
            if($CID===$actualCID){
                if($aSync){
                    echo''.$startDate.'';
                }else{
                    echo '1';
                }
            }else{
                echo '2';
            }
        }else{
            echo '0';
        }
    }
}
?>