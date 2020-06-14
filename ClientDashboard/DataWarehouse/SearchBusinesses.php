<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/adminValidation.php';
?>
<?php

$businessId=(isset($_POST['businessSearchChar']) ? explode(" ",mysqli_real_escape_string($conn,strtoupper($_POST['businessSearchChar'])))  : "");
$userId=(isset($_POST['usersSearchChar']) ?  explode(" ",mysqli_real_escape_string($conn,strtoupper($_POST['usersSearchChar']))) : "");
$deviceId=(isset($_POST['deviceSearchChar']) ? explode(" ",mysqli_real_escape_string($conn,strtoupper($_POST['deviceSearchChar'])))  : "");
$cid=(isset($_POST['cidSearchChar']) ? explode(" ",mysqli_real_escape_string($conn,strtoupper($_POST['cidSearchChar']))) : "");

if($_SESSION['uStatus']=='A'){
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr style="background-color: #0c3d78">
                    <th> </th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Code</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Description</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Insert date</Label></th>
                    <th> </th>
                </tr>';
}else{
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr style="background-color: #0c3d78">
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Code</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Description</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Insert date</Label></th>
                    <th> </th>
                </tr>';
}



$sqlWhere="";
$sqlselect = "SELECT b.BUSINESSID,b.CODE,b.DESCRIPTION,b.INSERT_DATE FROM businesses b WHERE 1=1 AND b.STATUS=1 ";
$sqlOrderBy="ORDER BY b.INSERT_DATE DESC,UPDATE_DATE DESC";

if($businessId != ""){
    foreach($businessId as $token){
        $sqlWhere.="AND (UPPER(b.CODE) LIKE '$token%' OR UPPER(b.CODE) LIKE '% $token%')";
    }
    //$sqlWhere = "AND UPPER(b.CODE) LIKE '%$businessId%' OR UPPER(b.CODE) LIKE '%_$businessId%'";
}
if($cid != ""){
    foreach($cid as $token){
        $sqlWhere.="AND b.BUSINESSID IN (SELECT c.BUSINESSID FROM cids c WHERE UPPER(c.CID) LIKE '$token%' OR UPPER(c.CID) LIKE '% $token%' )";
    }
    //$sqlWhere .= "AND b.BUSINESSID IN (SELECT c.BUSINESSID FROM cids c WHERE UPPER(c.CID) LIKE '$cid%' OR UPPER(c.CID) LIKE '%_$cid%' )";
}
if($userId != ""){
    foreach($userId as $token){
        $sqlWhere.="AND b.BUSINESSID IN (SELECT u.BUSINESSID FROM users u WHERE UPPER(u.USERNAMED) LIKE '$token%' OR UPPER(u.USERNAMED) LIKE '% $token%')";
    }
    //$sqlWhere .= "AND b.BUSINESSID IN (SELECT u.BUSINESSID FROM users u WHERE UPPER(u.USERNAMED) LIKE '$userId%' OR UPPER(u.USERNAMED) LIKE '%_$userId%')";
}
if($deviceId != ""){
    foreach($deviceId as $token){
        $sqlWhere.="AND b.BUSINESSID IN (SELECT d.BUSINESSID FROM devices d WHERE UPPER(d.DESCRIPTION) LIKE '$token%' OR UPPER(d.DESCRIPTION) LIKE '% $token%')";
    }
    //$sqlWhere .= "AND b.BUSINESSID IN (SELECT d.BUSINESSID FROM devices d WHERE UPPER(d.DESCRIPTION) LIKE '$deviceId%' OR UPPER(u.USERNAMED) LIKE '%_$deviceId%')";
}


$sqlstring = $sqlselect.$sqlWhere.$sqlOrderBy;

$sqlBusinessesSearch=mysqli_prepare($conn, $sqlstring);
if(mysqli_stmt_execute($sqlBusinessesSearch)){
    $rs=mysqli_stmt_get_result($sqlBusinessesSearch);
    while($result=mysqli_fetch_assoc($rs)){
        if($_SESSION['uStatus']=='A'){
            echo '  <tr id="dataRow">
                    <td id="'.$result["BUSINESSID"].'"><button type="submit" onclick="deleteFunction(this)" id="deleteBtn" name="deleteBtn" class="btn btn-danger btn-block btn-sm" style="font-size:small;">DELETE <span aria-hidden="true" class="glyphicon glyphicon-remove-circle"></span></button></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["CODE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["DESCRIPTION"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["INSERT_DATE"].'</Label></td>
                    <td id="'.$result["BUSINESSID"].'"><button type="submit" onclick="openBusiness(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }else{
            echo '  <tr id="dataRow">
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["CODE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["DESCRIPTION"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["INSERT_DATE"].'</Label></td>
                    <td id="'.$result["BUSINESSID"].'"><button type="submit" onclick="openBusiness(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }

    }
}

?>