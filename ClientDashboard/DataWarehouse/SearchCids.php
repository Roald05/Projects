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

$cid=(isset($_POST['cidSearchChar']) ? explode(" ",mysqli_real_escape_string($conn,strtoupper($_POST['cidSearchChar']))) : "");

if($_SESSION['uStatus']=='A'){
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr style="background-color: #0c3d78">
                    <th> </th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white;margin-left:50px">Cid</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Allow Sync</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white;;">Start Date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">BUSINESS</Label></th>
                    <th> </th>
                </tr>';
}else{
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr style="background-color: #0c3d78">
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white;margin-left:50px;">Cid</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Allow Sync</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Start Date</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">BUSINESS</Label></th>
                    <th> </th>
                </tr>';
}


$sqlWhere="";
$sqlselect = "SELECT * FROM cids c WHERE 1=1 AND c.STATUS=1 AND c.BUSINESSID='".$_SESSION['buCode']."'";


if($cid != ""){
    foreach($cid as $token){
        $sqlWhere.="AND (UPPER(c.CID) LIKE '$token%' OR UPPER(c.CID) LIKE '% $token%')";
    }
    //$sqlWhere .= "AND UPPER(c.CID) LIKE '%$cid%'";
}


$sqlstring = $sqlselect.$sqlWhere;


/*$sqlstring = "SELECT DISTINCT b.CODE,b.DESCRIPTION,b.INSERT_DATE
                                            FROM businesses b,cids c,devices d,users u
                                            WHERE b.BUSINESSID=c.BUSINESSID AND b.BUSINESSID=d.BUSINESSID AND b.BUSINESSID=u.BUSINESSID OR
                                            b.BUSINESSID LIKE '%$businessId%'

                                            OR d.DESCRIPTION LIKE '%$deviceId%'
                                            OR u.USERNAMED LIKE '%$cid%'";*/
$sqlCidsSearch=mysqli_prepare($conn, $sqlstring);
if(mysqli_stmt_execute($sqlCidsSearch)){
    $rs=mysqli_stmt_get_result($sqlCidsSearch);
    while($result=mysqli_fetch_assoc($rs)){
        if($_SESSION['uStatus']=='A'){
            echo '  <tr id="dataRow">
                    <td id="'.$result["CID"].'"><button type="submit" onclick="deleteCidFunction(this)" id="deleteBtn" name="deleteBtn" class="btn btn-danger btn-block btn-sm" style="font-size:small;">DELETE <span aria-hidden="true" class="glyphicon glyphicon-remove-circle"></span></button></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white;margin-left:50px;">'.$result["CID"].'</Label></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["ALLOW_SYNC"].'</Label></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["START_DATE"].'</Label></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$businessName.'</Label></td>
                    <td id="'.$result["CID"].'"><button type="submit" onclick="openCID(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }else{
            echo '  <tr id="dataRow">
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white;margin-left:50px;">'.$result["CID"].'</Label></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["ALLOW_SYNC"].'</Label></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["START_DATE"].'</Label></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$businessName.'</Label></td>
                    <td id="'.$result["CID"].'"><button type="submit" onclick="openCID(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }

    }
}

?>
<script >
    /*function function1(el){
     var bCode=document.getElementById('businessesTable').rows[el].cells[1].innerHTML;
     var bDesc=document.getElementById('businessesTable').rows[el.rowIndex].cells[2].innerHTML;

     myFunction(bCode,bDesc);
     }*/
</script>