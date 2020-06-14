<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/AdminValidation.php';
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


$userId=(isset($_POST['usersSearchChar']) ?  explode(" ",mysqli_real_escape_string($conn,strtoupper($_POST['usersSearchChar']))) : "");
if($_SESSION['uStatus']=='A'){
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr style="background-color: #0c3d78">
                    <th> </th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white;margin-left:50px;">Username</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">TIPI</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">BUSINESS</Label></th>
                    <th> </th>
                </tr>';
}else{
    echo'<div id="businessesTableDiv" style="background-color: #35a1bd" class="padding">
        <table id="businessesTable" style="text-align-all: center" class="table table-hover table-condensed boldtable ">
                <tr style="background-color: #0c3d78">
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white;margin-left:50px;">Username</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">TIPI</Label></th>
                    <th><Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">BUSINESS</Label></th>
                    <th> </th>
                </tr>';
}


$sqlWhere="";
$sqlselect = "SELECT u.USERNAMED,u.TIPI,u.FJALEKALIM,u.USERID FROM users u WHERE 1=1 AND u.STATUS=1 AND u.BUSINESSID='".$_SESSION['buCode']."' ";

if($userId != ""){
    foreach($userId as $token){
        $sqlWhere.="AND (UPPER(u.USERNAMED) LIKE '$token%' OR UPPER(u.USERNAMED) LIKE '% $token%') ";
    }
    //$sqlWhere .= "AND UPPER(u.USERNAMED) LIKE '%$userId%'";
}
if($_SESSION['uStatus']!='A'){
    $sqlWhere.="AND u.TIPI NOT IN ('A')";
}

$sqlstring = $sqlselect.$sqlWhere;

$sqlUsersSearch=mysqli_prepare($conn, $sqlstring);
if(mysqli_stmt_execute($sqlUsersSearch)){
    $rs=mysqli_stmt_get_result($sqlUsersSearch);
    while($result=mysqli_fetch_assoc($rs)){
        $userType="";
        switch($result["TIPI"]){
            case 'A':$userType='ADMIN';
                break;
            case 'S':$userType='SYSTEM';
                break;
            case 'B':$userType='BUSINESS';
                break;
        }
        if($_SESSION['uStatus']=='A'){
            echo '  <tr id="dataRow">
                    <td id="'.$result["USERID"].'"><button type="submit" onclick="deleteUserFunction(this)" id="deleteBtn" name="deleteBtn" class="btn btn-danger btn-block btn-sm" style="font-size:small;">DELETE <span aria-hidden="true" class="glyphicon glyphicon-remove-circle"></span></button></td>
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white;margin-left:50px;">'.$result["USERNAMED"].'</Label></td>
                    <td hidden><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["FJALEKALIM"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$userType.'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$businessName.'</Label></td>
                    <td id="'.$result["USERID"].'"><button type="submit" onclick="openUser(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }else{
            echo '  <tr id="dataRow">
                    <td><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white;margin-left:50px;">'.$result["USERNAMED"].'</Label></td>
                    <td hidden><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$result["FJALEKALIM"].'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$userType.'</Label></td>
                    <td ><Label ID="businessCodeLabel" class="control-label" style="font-size:12pt;font-weight:bold;color: white">'.$businessName.'</Label></td>
                    <td id="'.$result["USERID"].'"><button type="submit" onclick="openUser(this)" id="openBtn" name="openBtn" class="btn btn-primary btn-block btn-sm" style="font-size:small;">OPEN <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button></td>
                </tr>';
        }

    }
}

?>