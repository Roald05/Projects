<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/adminValidation.php';
?>
<?php

$businessName="";
$userName="SELECT CODE FROM businesses WHERE BUSINESSID='".$_SESSION['buCode']."' ";
$rs=mysqli_query($conn,$userName) or die(mysqli_error($conn));
if($rs != false){
    while($result=$rs->fetch_assoc()){
        $businessName=$result["CODE"];
    }
}

$deviceId=(isset($_POST['deviceSearchChar']) ? explode(" ",mysqli_real_escape_string($conn,strtoupper($_POST['deviceSearchChar'])))  : "");
if($_SESSION['uStatus']=='A'){
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr class="row" style="background-color: #0c3d78">
                    <th> </th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">KunderID</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Insert Date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Validation date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Expire date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Description</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">BUSINESS</Label></th>
                    <th> </th>
                </tr>';
}else{
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr class="row" style="background-color: #0c3d78">
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">KunderID</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Insert Date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Validation date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Expire date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Description</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">BUSINESS</Label></th>
                    <th> </th>
                </tr>';
}


$sqlWhere="";
$sqlselect = "SELECT * FROM devices d WHERE 1=1 AND d.STATUS=1 AND d.BUSINESSID='".$_SESSION['buCode']."' ";
$sqlOrderBy="ORDER BY d.INSERT_DATE DESC";

if($deviceId != ""){
    foreach($deviceId as $token){
        $sqlWhere.="AND (UPPER(d.DESCRIPTION) LIKE '$token%' OR UPPER(d.DESCRIPTION) LIKE '% $token%')";
    }
    //$sqlWhere = "AND UPPER(d.DESCRIPTION) LIKE '%$deviceId%'";
}


$sqlstring = $sqlselect.$sqlWhere.$sqlOrderBy;

$sqlDeviceSearch=mysqli_prepare($conn, $sqlstring);
if(mysqli_stmt_execute($sqlDeviceSearch)){
    $rs=mysqli_stmt_get_result($sqlDeviceSearch);
    while($result=mysqli_fetch_assoc($rs)){
        if($_SESSION['uStatus']=='A'){
            echo '  <tr class="row" id="dataRow">
                    <td id="'.$result["ID"].'"><button type="submit" onclick="deleteDeviceFunction(this)" id="deleteBtn" name="deleteBtn" class="btn btn-danger btn-block btn-sm" style="font-size:small;">DELETE <span aria-hidden="true" class="glyphicon glyphicon-remove-circle"></span></button></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["KUNDERID"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["INSERT_DATE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["VALIDATION_DATE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["EXPIRE_DATE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["DESCRIPTION"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$businessName.'</Label></td>
                    <td id="'.$result["ID"].'"><button type="submit" onclick="openDevices(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }else{
            echo '  <tr class="row" id="dataRow">
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["KUNDERID"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["INSERT_DATE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["VALIDATION_DATE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["EXPIRE_DATE"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["DESCRIPTION"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$businessName.'</Label></td>
                    <td id="'.$result["ID"].'"><button type="submit" onclick="openDevices(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }

    }
}

?>